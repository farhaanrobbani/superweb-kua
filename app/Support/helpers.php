<?php

use App\Models\KuaSetting;
use App\Models\NavbarItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

if (! function_exists('kua_setting')) {
    function kua_setting(string $key, ?string $default = null): ?string
    {
        try {
            return KuaSetting::get($key) ?? $default;
        } catch (Throwable) {
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
        } catch (Throwable) {
            return $defaults;
        }

        return $items->isEmpty() ? $defaults : $items;
    }
}

if (! function_exists('kua_navbar_page_url')) {
    function kua_navbar_page_url(string $key, ?string $default = null): ?string
    {
        $defaults = [
            'pengumuman' => '/pengumuman',
            'pernikahan' => '/pernikahan',
            'pengumuman-nikah' => '/pengumuman-nikah',
            'wakaf' => '/wakaf',
            'keagamaan' => '/keagamaan',
            'layanan-permohonan' => '/permohonan',
            'cari-akta' => '/cari-akta',
            'pegawai' => '/daftar-pegawai',
            'unduhan' => '/unduhan',
            'kritik-saran' => '/kritik-saran',
        ];

        $default = $default ?? ($defaults[$key] ?? null);

        try {
            $item = NavbarItem::query()
                ->where('key', $key)
                ->active()
                ->whereNotNull('url')
                ->where('url', '!=', '')
                ->ordered()
                ->first();

            return $item?->url ?: $default;
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('kua_navbar_page_label')) {
    function kua_navbar_page_label(string $key, ?string $default = null): ?string
    {
        $defaults = [
            'pengumuman' => 'Pengumuman',
            'pernikahan' => 'Layanan Pernikahan',
            'pengumuman-nikah' => 'Pengumuman Nikah',
            'wakaf' => 'Layanan Wakaf',
            'keagamaan' => 'Layanan Keagamaan',
            'layanan-permohonan' => 'Permohonan Surat',
            'cari-akta' => 'Pencarian Akta',
            'pegawai' => 'Daftar Pegawai',
            'unduhan' => 'Download Center',
            'kritik-saran' => 'Kritik & Saran',
        ];

        $default = $default ?? ($defaults[$key] ?? null);

        try {
            $item = NavbarItem::query()
                ->where('key', $key)
                ->active()
                ->whereNotNull('label')
                ->where('label', '!=', '')
                ->ordered()
                ->first();

            return $item?->label ?: $default;
        } catch (Throwable) {
            return $default;
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
            'j' => 'D',
            'm' => 'MM',
            'n' => 'M',
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

if (! function_exists('is_kua_open')) {
    function is_kua_open(?Carbon $at = null): bool
    {
        $now = $at ?? now();
        $dow = $now->dayOfWeekIso;

        if ($dow >= 6) {
            return false;
        }

        $open = $now->copy()->setTime(7, 30);
        $close = $now->copy()->setTime($dow === 5 ? 16 : 16, $dow === 5 ? 30 : 0);

        return $now->between($open, $close, true);
    }
}

if (! function_exists('kua_status_label')) {
    function kua_status_label(?Carbon $at = null): string
    {
        $now = $at ?? now();
        $dow = $now->dayOfWeekIso;

        if ($dow >= 6) {
            return 'Tutup • Buka Senin 07.30 WIB';
        }

        $open = $now->copy()->setTime(7, 30);
        $close = $now->copy()->setTime($dow === 5 ? 16 : 16, $dow === 5 ? 30 : 0);
        $closeLabel = $dow === 5 ? '16.30' : '16.00';

        if ($now->lt($open)) {
            return 'Tutup • Buka 07.30 WIB';
        }

        if ($now->gt($close)) {
            return $dow === 5 ? 'Tutup • Buka Senin 07.30 WIB' : 'Tutup • Buka besok 07.30 WIB';
        }

        return "Buka • Tutup {$closeLabel} WIB";
    }
}
