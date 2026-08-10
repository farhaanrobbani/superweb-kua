<?php

namespace Database\Seeders;

use App\Models\NavbarItem;
use Illuminate\Database\Seeder;

class NavbarItemSeeder extends Seeder
{
    public function run(): void
    {
        $mainItems = [
            ['key' => 'beranda', 'label' => 'Beranda', 'url' => '/', 'sort_order' => 1, 'has_submenu' => false],
            ['key' => 'layanan', 'label' => 'Layanan', 'url' => null, 'sort_order' => 2, 'has_submenu' => true],
            ['key' => 'pengumuman', 'label' => 'Pengumuman', 'url' => '/pengumuman', 'sort_order' => 3, 'has_submenu' => false],
            ['key' => 'tentang', 'label' => 'Tentang Kami', 'url' => null, 'sort_order' => 4, 'has_submenu' => true],
        ];

        foreach ($mainItems as $item) {
            NavbarItem::firstOrCreate(['key' => $item['key']], $item);
        }

        $layanan = NavbarItem::where('key', 'layanan')->first();
        $tentang = NavbarItem::where('key', 'tentang')->first();

        $subItems = [
            ['key' => 'pernikahan', 'label' => 'Pernikahan', 'url' => '/pernikahan', 'parent_id' => $layanan?->id, 'sort_order' => 1],
            ['key' => 'wakaf', 'label' => 'Wakaf', 'url' => '/wakaf', 'parent_id' => $layanan?->id, 'sort_order' => 2],
            ['key' => 'keagamaan', 'label' => 'Keagamaan', 'url' => '/keagamaan', 'parent_id' => $layanan?->id, 'sort_order' => 3],
            ['key' => 'layanan-permohonan', 'label' => 'Pengajuan Surat Online', 'url' => '/permohonan', 'parent_id' => $layanan?->id, 'sort_order' => 4],
            ['key' => 'cari-akta', 'label' => 'Pencarian Akta', 'url' => '/cari-akta', 'parent_id' => $layanan?->id, 'sort_order' => 5],
            ['key' => 'pegawai', 'label' => 'Daftar Pegawai', 'url' => '/daftar-pegawai', 'parent_id' => $tentang?->id, 'sort_order' => 1],
            ['key' => 'unduhan', 'label' => 'Download Center', 'url' => '/unduhan', 'parent_id' => $tentang?->id, 'sort_order' => 2],
            ['key' => 'kritik-saran', 'label' => 'Kritik & Saran', 'url' => '/kritik-saran', 'parent_id' => $tentang?->id, 'sort_order' => 3],
        ];

        foreach ($subItems as $item) {
            if ($item['parent_id'] === null) {
                continue;
            }

            NavbarItem::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
