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
        $setting = static::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
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
