<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * Key/value settings managed from Admin → Settings.
 * Keys listed in SECRET are encrypted at rest with APP_KEY.
 */
class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    public const SECRET = ['mail_password', 'whatsapp_token'];
    private const CACHE_KEY = 'settings.all';

    /** All settings as [key => decrypted value]; cached. */
    public static function allValues(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return static::query()->pluck('value', 'key')->map(function ($v, $k) {
                    if ($v !== null && in_array($k, self::SECRET, true)) {
                        try { return Crypt::decryptString($v); } catch (\Throwable) { return null; }
                    }
                    return $v;
                })->all();
            });
        } catch (\Throwable) {
            return []; // table not migrated yet / DB down: fall back to .env values, cache nothing
        }
    }

    public static function get(string $key, $default = null)
    {
        $v = static::allValues()[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    /** Save many values. Empty secret fields are skipped so saved passwords are kept. */
    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            if (in_array($key, self::SECRET, true)) {
                if ($value === null || $value === '') continue;
                $value = Crypt::encryptString($value);
            }
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    public static function has(string $key): bool
    {
        return (static::allValues()[$key] ?? '') !== '';
    }
}
