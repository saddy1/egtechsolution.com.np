<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DateConvertController extends Controller
{
    private string $base = 'https://sudhang.pythonanywhere.com';

    public function bsToAd(Request $request)
    {
        $bs = trim((string) $request->query('bs', '')); // expects YYYY-MM-DD or YYYY/MM/DD

        if (!preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $bs)) {
            return response()->json(['ok' => false, 'error' => 'invalid_format', 'hint' => 'Use YYYY-MM-DD'], 422);
        }

        $formatted = str_replace('-', '/', $bs); // API wants slashes
        try {
            $resp = Http::timeout(8)->get("{$this->base}/BStoAD/{$formatted}");
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => 'upstream_unreachable'], 502);
        }

        if (!$resp->ok()) {
            return response()->json(['ok' => false, 'error' => 'upstream_'.$resp->status()], 502);
        }

        $data = $resp->json();
        if (!is_array($data) || empty($data['ad_date']['year'])) {
            return response()->json(['ok' => false, 'error' => 'bad_upstream_payload'], 502);
        }

        $y = $data['ad_date']['year'];
        $m = str_pad((string)$data['ad_date']['month'], 2, '0', STR_PAD_LEFT);
        $d = str_pad((string)$data['ad_date']['day'],   2, '0', STR_PAD_LEFT);

        return response()->json(['ok' => true, 'ad' => "{$y}-{$m}-{$d}"]);
    }

    // Optional reverse (if your upstream supports ADtoBS/YYYY/MM/DD)
    public function adToBs(Request $request)
    {
        $ad = trim((string) $request->query('ad', ''));

        if (!preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $ad)) {
            return response()->json(['ok' => false, 'error' => 'invalid_format', 'hint' => 'Use YYYY-MM-DD'], 422);
        }

        $formatted = str_replace('-', '/', $ad);
        try {
            $resp = Http::timeout(8)->get("{$this->base}/ADtoBS/{$formatted}");
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => 'upstream_unreachable'], 502);
        }

        if (!$resp->ok()) {
            return response()->json(['ok' => false, 'error' => 'upstream_'.$resp->status()], 502);
        }

        $data = $resp->json();
        if (!is_array($data) || empty($data['bs_date']['year'])) {
            return response()->json(['ok' => false, 'error' => 'bad_upstream_payload'], 502);
        }

        $y = $data['bs_date']['year'];
        $m = str_pad((string)$data['bs_date']['month'], 2, '0', STR_PAD_LEFT);
        $d = str_pad((string)$data['bs_date']['day'],   2, '0', STR_PAD_LEFT);

        return response()->json(['ok' => true, 'bs' => "{$y}-{$m}-{$d}"]);
    }
}
