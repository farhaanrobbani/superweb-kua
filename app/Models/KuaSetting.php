<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['key', 'value'])]
class KuaSetting extends Model
{
    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = static::cachedAll();

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        if (app()->bound('kua_settings.cache')) {
            $cache = app('kua_settings.cache');
            $cache[$key] = $value;
            app()->instance('kua_settings.cache', $cache);
        }
    }

    private static function cachedAll(): array
    {
        if (app()->bound('kua_settings.cache')) {
            return app('kua_settings.cache');
        }

        $settings = static::pluck('value', 'key')->all();
        app()->instance('kua_settings.cache', $settings);

        return $settings;
    }

    public static function logoUrl(): ?string
    {
        return self::storedImageUrl('logo_path');
    }

    public static function heroUrl(): ?string
    {
        return self::storedImageUrl('hero_path');
    }

    public static function backgroundUrl(): ?string
    {
        return self::storedImageUrl('bg_path');
    }

    private static function storedImageUrl(string $key): ?string
    {
        try {
            $path = static::get($key);

            if (blank($path) || ! Storage::disk('public')->exists($path)) {
                return null;
            }

            return Storage::url($path);
        } catch (\Throwable) {
            return null;
        }
    }
}
