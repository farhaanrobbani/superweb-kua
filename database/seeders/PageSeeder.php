<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'beranda' => [
                'title' => 'Beranda',
                'description' => null,
                'content' => null,
            ],
            'pengumuman' => [
                'title' => 'Pengumuman',
                'description' => 'Informasi dan pengumuman resmi dari Kantor Urusan Agama.',
                'content' => null,
            ],
            'pernikahan' => [
                'title' => 'Layanan Pernikahan',
                'description' => 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan pernikahan di KUA. Beberapa layanan dapat diajukan secara online melalui tombol ajukan.',
                'content' => null,
            ],
            'unduhan' => [
                'title' => 'Download Center',
                'description' => 'Unduh berkas, formulir, dan dokumen resmi yang dibutuhkan.',
                'content' => null,
            ],
            'daftar-pegawai' => [
                'title' => 'Struktur Organisasi',
                'description' => 'Daftar pegawai dan struktur organisasi Kantor Urusan Agama.',
                'content' => null,
            ],
            'kritik-saran' => [
                'title' => 'Kritik & Saran',
                'description' => 'Sampaikan kritik, saran, atau masukan Anda untuk meningkatkan pelayanan Kantor Urusan Agama.',
                'content' => null,
            ],
            'permohonan' => [
                'title' => 'Form Permohonan Surat',
                'description' => 'Isi form berikut, kemudian petugas KUA akan memproses permohonan Anda.',
                'content' => null,
            ],
            'layanan' => [
                'title' => 'Layanan Online',
                'description' => 'Layanan surat dan informasi KUA yang dapat diakses secara online.',
                'content' => null,
            ],
        ];

        foreach ($pages as $key => $data) {
            Page::updateOrCreate(
                ['key' => $key],
                $data + ['active' => true]
            );
        }
    }
}