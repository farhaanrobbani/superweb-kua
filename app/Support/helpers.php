<?php

use App\Models\KuaSetting;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

if (! function_exists('kua_setting')) {
    function kua_setting(string $key, ?string $default = null): ?string
    {
        try {
            return KuaSetting::get($key) ?? $default;
        } catch (\Throwable) {
            return $default;
        }
    }
}

if (! function_exists('kua_services')) {
    function kua_services(): Collection
    {
        try {
            return Service::query()->active()->ordered()->get(['name', 'slug', 'description', 'content', 'url', 'icon']);
        } catch (\Throwable) {
            return collect();
        }
    }
}

if (! function_exists('tanggal_indonesia')) {
    function tanggal_indonesia(Carbon|string $date, string $format = 'd F Y'): string
    {
        if (! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $isoFormat = strtr($format, [
            'd' => 'DD',
            'F' => 'MMMM',
            'Y' => 'YYYY',
            'l' => 'dddd',
            'D' => 'ddd',
        ]);

        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('id');
        $result = $date->isoFormat($isoFormat);
        Carbon::setLocale($previousLocale);

        return $result;
    }
}
