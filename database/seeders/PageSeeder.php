<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['key' => 'pernikahan'],
            [
                'title' => 'Layanan Pernikahan',
                'description' => 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan pernikahan di KUA. Beberapa layanan dapat diajukan secara online melalui tombol ajukan.',
                'embed_url' => null,
                'active' => true,
            ]
        );

        Page::updateOrCreate(
            ['key' => 'cari-akta'],
            [
                'title' => 'Pencarian Akta',
                'description' => 'Fitur pencarian nomor akta nikah memudahkan masyarakat untuk mengecek data akta nikah. Jika data ditemukan, mohon segera menghubungi KUA Ampelgading untuk informasi dan layanan lebih lanjut.',
                'embed_url' => null,
                'active' => true,
            ]
        );

        Page::updateOrCreate(
            ['key' => 'wakaf'],
            [
                'title' => 'Wakaf',
                'description' => null,
                'embed_url' => null,
                'active' => true,
            ]
        );

        Page::updateOrCreate(
            ['key' => 'keagamaan'],
            [
                'title' => 'Layanan Keagamaan',
                'description' => 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan keagamaan di KUA, termasuk bimbingan perkawinan, penyuluhan agama, dan pembinaan umat beragama.',
                'embed_url' => null,
                'active' => true,
            ]
        );
    }
}
