<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sends WhatsApp messages from the shop's business number through Meta's official Cloud API.
 * Business-initiated messages must use templates approved in WhatsApp Manager.
 * Settings live in Admin → Settings → WhatsApp.
 */
class WhatsAppService
{
    public function enabled(): bool
    {
        return Setting::get('whatsapp_enabled') === '1'
            && Setting::has('whatsapp_phone_number_id')
            && Setting::has('whatsapp_token');
    }

    /** Nepal-friendly: "98XXXXXXXX", "+977 98…", "00977…" → "97798…". Null if not a usable number. */
    public static function normalize(?string $phone): ?string
    {
        $d = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($d, '00')) $d = substr($d, 2);
        if (strlen($d) === 10 && str_starts_with($d, '9')) $d = '977' . $d;   // local mobile
        if (strlen($d) === 11 && str_starts_with($d, '0')) $d = '977' . substr($d, 1);
        return strlen($d) >= 11 && strlen($d) <= 15 ? $d : null;
    }

    /**
     * Send an approved template. $params fill {{1}}, {{2}}, … in the template body.
     * @return array{ok: bool, error?: string, id?: string}
     */
    public function sendTemplate(string $to, string $template, array $params = [], ?string $lang = null): array
    {
        $number = self::normalize($to);
        if (!$number) return ['ok' => false, 'error' => "Invalid phone number: {$to}"];

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $number,
            'type'              => 'template',
            'template'          => [
                'name'     => $template,
                'language' => ['code' => $lang ?: Setting::get('whatsapp_template_lang', 'en')],
            ],
        ];

        if ($params) {
            $payload['template']['components'] = [[
                'type'       => 'body',
                // WhatsApp rejects new lines, tabs and 4+ spaces inside template variables
                'parameters' => array_map(fn ($p) => ['type' => 'text', 'text' => Str::limit(trim(preg_replace('/\s+/', ' ', (string) $p)), 900)], $params),
            ]];
        }

        $url = sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            Setting::get('whatsapp_api_version', 'v23.0'),
            Setting::get('whatsapp_phone_number_id')
        );

        try {
            $resp = Http::withToken((string) Setting::get('whatsapp_token'))->timeout(15)->asJson()->post($url, $payload);
        } catch (\Throwable $e) {
            Log::warning('WhatsApp send failed', ['to' => $number, 'template' => $template, 'error' => $e->getMessage()]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }

        if ($resp->failed()) {
            $err = data_get($resp->json(), 'error.error_data.details') ?: data_get($resp->json(), 'error.message') ?: ('HTTP ' . $resp->status());
            Log::warning('WhatsApp send failed', ['to' => $number, 'template' => $template, 'status' => $resp->status(), 'body' => $resp->json()]);
            return ['ok' => false, 'error' => $err];
        }

        return ['ok' => true, 'id' => data_get($resp->json(), 'messages.0.id')];
    }

    /** New-order alerts: one to the shop, one to the customer. Never throws. */
    public function notifyNewOrder(int $orderId): void
    {
        if (!$this->enabled()) return;

        $order = Order::with('items')->find($orderId);
        if (!$order) return;

        $total = number_format((float) $order->total);
        $items = $order->items->map(fn ($i) => "{$i->quantity}x {$i->product_name}")->implode(', ');

        $shopNumber = Setting::get('whatsapp_shop_number');
        $shopTemplate = Setting::get('whatsapp_shop_template');
        if ($shopNumber && $shopTemplate) {
            // {{1}} order no, {{2}} customer name, {{3}} customer phone, {{4}} total, {{5}} items
            $this->sendTemplate($shopNumber, $shopTemplate, [
                $order->id, $order->name, $order->phone, $total, Str::limit($items ?: '-', 300),
            ]);
        }

        $customerTemplate = Setting::get('whatsapp_customer_template');
        if ($order->phone && $customerTemplate) {
            // {{1}} customer name, {{2}} order no, {{3}} total
            $this->sendTemplate($order->phone, $customerTemplate, [
                Str::limit($order->name, 60, ''), $order->id, $total,
            ]);
        }
    }

    /** Queue the alerts to run after the page is sent, so checkout never waits on WhatsApp. */
    public static function dispatchOrderAlerts(int $orderId): void
    {
        dispatch(function () use ($orderId) {
            try {
                app(WhatsAppService::class)->notifyNewOrder($orderId);
            } catch (\Throwable $e) {
                report($e);
            }
        })->afterResponse();
    }
}
