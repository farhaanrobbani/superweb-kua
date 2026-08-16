<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Jam Layanan Kantor KUA',
                'content' => "Kantor Urusan Agama melayani masyarakat pada:\n\nSenin – Jumat : 08.00 – 15.00 WIB\nSabtu : 08.00 – 12.00 WIB\n\nMohon datang sesuai jam layanan agar permohonan dapat diproses dengan baik.",
                'published_at' => '2026-08-04 09:00:00',
            ],
            [
                'title' => 'Layanan Pengajuan Surat Secara Online',
                'content' => "Masyarakat kini dapat mengajukan permohonan surat secara online melalui menu Layanan – Pengajuan Surat Online tanpa harus datang ke kantor.\n\nSetelah mengirim permohonan, petugas akan memproses dan menghubungi Anda melalui kontak yang didaftarkan.",
                'published_at' => '2026-08-03 10:30:00',
            ],
            [
                'title' => 'Libur Nasional: HUT Republik Indonesia',
                'content' => "Sehubungan dengan peringatan HUT ke-81 Republik Indonesia, Kantor Urusan Agama tutup pada tanggal 17 Agustus 2026.\n\nLayanan kembali dibuka seperti biasa pada hari berikutnya. Mohon maaf atas ketidaknyamanannya.",
                'published_at' => '2026-08-01 08:00:00',
            ],
            [
                'title' => 'Pengambilan Surat yang Telah Terbit',
                'content' => "Bagi masyarakat yang suratnya telah terbit, pengambilan dilakukan di loket pelayanan KUA dengan membawa tanda bukti pengajuan dan dokumen persyaratan asli.\n\nKami juga menyediakan salinan digital yang dapat diunduh dari petugas kami.",
                'published_at' => '2026-07-28 13:00:00',
            ],
            [
                'title' => 'Pemeliharaan Sistem Layanan Online',
                'content' => "Kami akan melakukan pemeliharaan sistem layanan online pada Minggu, 2 Agustus 2026 pukul 22.00 – 24.00 WIB.\n\nSelama pemeliharaan, pengajuan permohonan online tidak dapat dilakukan. Terima kasih atas pengertiannya.",
                'published_at' => '2026-07-25 09:00:00',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                [
                    'content' => $announcement['content'],
                    'excerpt' => Str::limit(strip_tags($announcement['content']), 160),
                    'slug' => Str::slug($announcement['title']),
                    'category' => 'announcement',
                    'published_at' => $announcement['published_at'],
                    'active' => true,
                ]
            );
        }
    }
}
