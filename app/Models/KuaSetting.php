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
        try {
            $path = static::get('logo_path');

            if (blank($path) || ! Storage::disk('public')->exists($path)) {
                return null;
            }

            return Storage::url($path);
        } catch (\Throwable) {
            return null;
        }
    }
}
