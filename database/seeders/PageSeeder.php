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
                'embed_url' => 'https://datastudio.google.com/embed/reporting/e04bd5b7-c300-40f7-973d-60379a88b930/page/gPzuF',
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
                'title' => 'Keagamaan',
                'description' => null,
                'embed_url' => null,
                'active' => true,
            ]
        );
    }
}
