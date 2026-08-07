<?php

use App\Models\KuaSetting;
use App\Models\NavbarItem;
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
            return Service::query()->active()->ordered()->get(['name', 'description', 'url', 'icon']);
        } catch (\Throwable) {
            return collect();
        }
    }
}

if (! function_exists('kua_navbar')) {
    function kua_navbar(): Collection
    {
        $defaults = collect([
            (object) ['key' => 'beranda', 'label' => 'Beranda', 'url' => '/', 'has_submenu' => false],
            (object) ['key' => 'layanan', 'label' => 'Layanan', 'url' => null, 'has_submenu' => true],
            (object) ['key' => 'pengumuman', 'label' => 'Pengumuman', 'url' => '/pengumuman', 'has_submenu' => false],
            (object) ['key' => 'tentang', 'label' => 'Tentang Kami', 'url' => null, 'has_submenu' => true],
        ]);

        try {
            $items = NavbarItem::query()
                ->where('group', NavbarItem::GROUP_MAIN)
                ->active()
                ->ordered()
                ->get();
        } catch (\Throwable) {
            return $defaults;
        }

        return $items->isEmpty() ? $defaults : $items;
    }
}

if (! function_exists('kua_navbar_tentang')) {
    function kua_navbar_tentang(): Collection
    {
        $defaults = collect([
            (object) ['label' => 'Daftar Pegawai', 'url' => '/daftar-pegawai'],
            (object) ['label' => 'Download Center', 'url' => '/unduhan'],
            (object) ['label' => 'Kritik & Saran', 'url' => '/kritik-saran'],
        ]);

        try {
            $items = NavbarItem::query()
                ->where('group', NavbarItem::GROUP_TENTANG)
                ->active()
                ->ordered()
                ->get();
        } catch (\Throwable) {
            return $defaults;
        }

        return $items->isEmpty() ? $defaults : $items;
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
