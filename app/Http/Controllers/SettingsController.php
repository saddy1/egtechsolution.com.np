<?php

namespace App\Http\Controllers;

use App\Models\AdminPasskey;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/** Admin → Settings: email (SMTP), WhatsApp Cloud API, passkeys. */
class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $s = Setting::allValues();
        $tab = in_array($request->query('tab'), ['email', 'whatsapp', 'security'], true) ? $request->query('tab') : 'email';
        $passkeys = AdminPasskey::where('admin_id', Auth::guard('admin')->id())->latest()->get();

        // Show .env values as the starting point until something is saved here
        $mail = [
            'host'       => $s['mail_host'] ?? config('mail.mailers.smtp.host'),
            'port'       => $s['mail_port'] ?? config('mail.mailers.smtp.port'),
            'encryption' => $s['mail_encryption'] ?? (config('mail.mailers.smtp.encryption') ?: 'none'),
            'username'   => $s['mail_username'] ?? config('mail.mailers.smtp.username'),
            'from_address' => $s['mail_from_address'] ?? config('mail.from.address'),
            'from_name'  => $s['mail_from_name'] ?? config('mail.from.name'),
            'has_password' => !empty($s['mail_password']) || config('mail.mailers.smtp.password'),
        ];

        return view('backend.settings.index', compact('s', 'tab', 'mail', 'passkeys'));
    }

    public function saveMail(Request $request)
    {
        $data = $request->validate([
            'mail_host'         => ['required', 'string', 'max:190'],
            'mail_port'         => ['required', 'integer', 'between:1,65535'],
            'mail_encryption'   => ['required', 'in:tls,ssl,none'],
            'mail_username'     => ['nullable', 'string', 'max:190'],
            'mail_password'     => ['nullable', 'string', 'max:300'],
            'mail_from_address' => ['required', 'email', 'max:190'],
            'mail_from_name'    => ['required', 'string', 'max:100'],
        ]);

        // Gmail app passwords are shown with spaces; SMTP needs them without
        if (!empty($data['mail_password']) && str_contains(strtolower($data['mail_host']), 'gmail')) {
            $data['mail_password'] = str_replace(' ', '', $data['mail_password']);
        }

        Setting::put($data);
        return redirect()->route('admin.settings', ['tab' => 'email'])->with('success', 'Email settings saved. Send a test email to check them.');
    }

    public function testMail(Request $request)
    {
        $data = $request->validate(['to' => ['required', 'email']]);

        try {
            Mail::raw("This is a test email from EG Tech Solution admin.\n\nIf you can read this, your SMTP settings work.", function ($m) use ($data) {
                $m->to($data['to'])->subject('Test email from EG Tech Solution');
            });
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings', ['tab' => 'email'])
                ->with('error', 'Sending failed: ' . $this->friendlyMailError($e->getMessage()));
        }

        return redirect()->route('admin.settings', ['tab' => 'email'])->with('success', "Test email sent to {$data['to']}. Check the inbox (and spam).");
    }

    public function saveWhatsApp(Request $request)
    {
        $data = $request->validate([
            'whatsapp_enabled'           => ['nullable', 'in:1'],
            'whatsapp_phone_number_id'   => ['nullable', 'regex:/^\d{5,30}$/'],
            'whatsapp_token'             => ['nullable', 'string', 'max:1000'],
            'whatsapp_api_version'       => ['required', 'regex:/^v\d{2}\.\d$/'],
            'whatsapp_shop_number'       => ['nullable', 'string', 'max:30'],
            'whatsapp_shop_template'     => ['nullable', 'regex:/^[a-z0-9_]{1,512}$/'],
            'whatsapp_customer_template' => ['nullable', 'regex:/^[a-z0-9_]{1,512}$/'],
            'whatsapp_template_lang'     => ['required', 'regex:/^[a-z]{2}(_[A-Z]{2})?$/'],
        ], [
            'whatsapp_phone_number_id.regex' => 'Phone number ID is the long number from WhatsApp Manager → API setup (digits only), not your phone number.',
            '*.regex' => 'Template names use lowercase letters, numbers and underscores only.',
        ]);

        if (!empty($data['whatsapp_shop_number']) && !WhatsAppService::normalize($data['whatsapp_shop_number'])) {
            return back()->withInput()->withErrors(['whatsapp_shop_number' => 'Enter a full mobile number, e.g. 98XXXXXXXX or +977 98XXXXXXXX.']);
        }

        $data['whatsapp_enabled'] = $request->boolean('whatsapp_enabled') ? '1' : '0';
        Setting::put($data);

        return redirect()->route('admin.settings', ['tab' => 'whatsapp'])->with('success', 'WhatsApp settings saved.');
    }

    public function testWhatsApp(Request $request, WhatsAppService $wa)
    {
        $data = $request->validate([
            'to'       => ['required', 'string', 'max:30'],
            'template' => ['required', 'regex:/^[a-z0-9_]{1,512}$/'],
        ]);

        if (!Setting::has('whatsapp_phone_number_id') || !Setting::has('whatsapp_token')) {
            return redirect()->route('admin.settings', ['tab' => 'whatsapp'])->with('error', 'Save the Phone number ID and access token first.');
        }

        // hello_world (Meta's sample) takes no variables and is en_US; our own templates get sample values
        $params = match ($data['template']) {
            'hello_world' => [],
            Setting::get('whatsapp_shop_template') => ['1001', 'Test Customer', '9800000000', '1,25,000', '1x Test Laptop'],
            Setting::get('whatsapp_customer_template') => ['Test Customer', '1001', '1,25,000'],
            default => [],
        };
        $lang = $data['template'] === 'hello_world' ? 'en_US' : null;

        $res = $wa->sendTemplate($data['to'], $data['template'], $params, $lang);

        return redirect()->route('admin.settings', ['tab' => 'whatsapp'])->with(
            $res['ok'] ? 'success' : 'error',
            $res['ok'] ? "Test message sent to {$data['to']}." : 'WhatsApp said: ' . $res['error']
        );
    }

    private function friendlyMailError(string $msg): string
    {
        if (str_contains($msg, '535') || stripos($msg, 'Username and Password not accepted') !== false) {
            return 'The username or password was rejected. For Gmail, turn on 2-Step Verification and use a 16-letter App Password, not your normal password.';
        }
        if (stripos($msg, 'Connection could not be established') !== false || stripos($msg, 'timed out') !== false) {
            return 'Could not connect to the mail server. Check the host and port (Gmail: smtp.gmail.com, 587 with TLS or 465 with SSL).';
        }
        return $msg;
    }
}
