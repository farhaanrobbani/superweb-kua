<?php

use App\Models\KuaSetting;
use App\Models\NavbarItem;
use App\Models\Page;
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

if (! function_exists('kua_navbar')) {
    function kua_navbar(): Collection
    {
        $defaults = collect([
            (object) ['key' => 'beranda', 'label' => 'Beranda', 'url' => '/', 'has_submenu' => false, 'children' => collect()],
            (object) [
                'key' => 'layanan',
                'label' => 'Layanan',
                'url' => null,
                'has_submenu' => true,
                'children' => collect([
                    (object) ['label' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'icon' => null, 'active' => true],
                ]),
            ],
            (object) ['key' => 'pengumuman', 'label' => 'Pengumuman', 'url' => '/pengumuman', 'has_submenu' => false, 'children' => collect()],
            (object) [
                'key' => 'tentang',
                'label' => 'Tentang Kami',
                'url' => null,
                'has_submenu' => true,
                'children' => collect([
                    (object) ['label' => 'Daftar Pegawai', 'url' => '/daftar-pegawai', 'icon' => null, 'active' => true],
                    (object) ['label' => 'Download Center', 'url' => '/unduhan', 'icon' => null, 'active' => true],
                    (object) ['label' => 'Kritik & Saran', 'url' => '/kritik-saran', 'icon' => null, 'active' => true],
                ]),
            ],
        ]);

        try {
            $items = NavbarItem::query()
                ->root()
                ->active()
                ->ordered()
                ->with('children')
                ->get();
        } catch (\Throwable) {
            return $defaults;
        }

        return $items->isEmpty() ? $defaults : $items;
    }
}

if (! function_exists('kua_page')) {
    function kua_page(string $key): ?Page
    {
        try {
            return Page::active()->where('key', $key)->first();
        } catch (\Throwable) {
            return null;
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
