<?php

namespace Database\Seeders;

use App\Models\NavbarItem;
use Illuminate\Database\Seeder;

class NavbarItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'beranda', 'label' => 'Beranda', 'url' => '/', 'group' => NavbarItem::GROUP_MAIN, 'sort_order' => 1, 'has_submenu' => false],
            ['key' => 'layanan', 'label' => 'Layanan', 'url' => null, 'group' => NavbarItem::GROUP_MAIN, 'sort_order' => 2, 'has_submenu' => true],
            ['key' => 'pengumuman', 'label' => 'Pengumuman', 'url' => '/pengumuman', 'group' => NavbarItem::GROUP_MAIN, 'sort_order' => 3, 'has_submenu' => false],
            ['key' => 'tentang', 'label' => 'Tentang Kami', 'url' => null, 'group' => NavbarItem::GROUP_MAIN, 'sort_order' => 4, 'has_submenu' => true],
            ['key' => 'pegawai', 'label' => 'Daftar Pegawai', 'url' => '/daftar-pegawai', 'group' => NavbarItem::GROUP_TENTANG, 'sort_order' => 1, 'has_submenu' => false],
            ['key' => 'unduhan', 'label' => 'Download Center', 'url' => '/unduhan', 'group' => NavbarItem::GROUP_TENTANG, 'sort_order' => 2, 'has_submenu' => false],
            ['key' => 'kritik-saran', 'label' => 'Kritik & Saran', 'url' => '/kritik-saran', 'group' => NavbarItem::GROUP_TENTANG, 'sort_order' => 3, 'has_submenu' => false],
        ];

        foreach ($items as $item) {
            NavbarItem::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
