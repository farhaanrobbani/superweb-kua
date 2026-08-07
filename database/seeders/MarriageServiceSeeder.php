<?php

namespace Database\Seeders;

use App\Models\MarriageService;
use Illuminate\Database\Seeder;

class MarriageServiceSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Pendaftaran Nikah',
                'slug' => 'pendaftaran-nikah',
                'icon' => 'heart',
                'sort_order' => 1,
                'description' => 'Pendaftaran kehendak menikah dan pemeriksaan berkas calon pengantin di KUA.',
                'persyaratan' => "Surat pengantar dari kelurahan/desa (formulir N1-N4)\nFoto copy KTP dan Kartu Keluarga (KK) calon pengantin\nAkta kelahiran; akta cerai/akta kematian suami atau istri bagi duda/janda\nSurat izin orang tua (calon pengantin di bawah usia 21 tahun)\nSurat izin atasan (anggota TNI/Polri/PNS)\nPas foto berwarna sesuai ketentuan\nSurat keterangan domisili (jika berbeda dengan tempat pendaftaran)",
                'alur' => "Calon pengantin datang ke KUA membawa berkas persyaratan\nPetugas melakukan pemeriksaan kelengkapan dan keabsahan berkas\nPengumuman kehendak nikah selama 10 hari kerja\nPelaksanaan akad nikah sesuai jadwal yang disepakati\nPencatatan perkawinan dan penerbitan buku nikah",
                'sop' => "Calon pengantin mengisi formulir pendaftaran nikah\nPenghulu meneliti berkas dan dokumen calon pengantin\nKepala KUA menetapkan jadwal akad nikah\nAkad nikah dilaksanakan dengan disaksikan saksi dan dihadiri wali\nBuku nikah diserahkan kepada kedua mempelai",
            ],
            [
                'name' => 'Rekomendasi Nikah',
                'slug' => 'rekomendasi-nikah',
                'icon' => 'document',
                'sort_order' => 2,
                'description' => 'Rekomendasi bagi Warga Negara Indonesia yang akan melangsungkan perkawinan di luar negeri.',
                'persyaratan' => "Foto copy KTP dan Kartu Keluarga (KK)\nAkta kelahiran\nSurat keterangan dari kelurahan/desa\nSurat izin orang tua/atasan (jika dipersyaratkan)\nFoto copy paspor yang masih berlaku\nPas foto terbaru",
                'alur' => "Pemohon datang ke KUA membawa berkas persyaratan\nPetugas memverifikasi kelengkapan dan keabsahan berkas\nPenerbitan surat rekomendasi nikah oleh Kepala KUA\nPemohon menerima dokumen rekomendasi",
                'sop' => "Petugas meneliti kelengkapan berkas pemohon\nKepala KUA menerbitkan rekomendasi nikah\nPemohon menandatangani tanda terima dokumen",
            ],
            [
                'name' => 'Rujuk',
                'slug' => 'rujuk',
                'icon' => 'heart',
                'sort_order' => 3,
                'description' => 'Pendaftaran dan pencatatan rujuk bagi pasangan suami istri yang telah bercerai talak.',
                'persyaratan' => "Akta cerai dari pengadilan agama\nFoto copy KTP dan Kartu Keluarga (KK)\nSurat pengantar dari kelurahan/desa\nAkta kelahiran anak (jika ada)\nPas foto terbaru",
                'alur' => "Pemohon mendaftar rujuk di KUA membawa berkas\nPetugas melakukan pemeriksaan dan pengumuman\nPelaksanaan rujuk sesuai ketentuan\nPencatatan rujuk dan penerbitan dokumen",
                'sop' => "Petugas memeriksa kelengkapan berkas rujuk\nPenghulu menetapkan jadwal pelaksanaan rujuk\nRujuk dicatat dan dilaporkan sesuai ketentuan",
            ],
            [
                'name' => 'Legalisir',
                'slug' => 'legalisir',
                'icon' => 'check',
                'sort_order' => 4,
                'description' => 'Pengesahan salinan buku nikah/akta nikah agar memiliki kekuatan hukum setara dokumen asli.',
                'persyaratan' => "Buku nikah atau salinan akta nikah yang akan dilegalisir\nFoto copy KTP pemohon",
                'alur' => "Pemohon datang ke KUA membawa dokumen yang akan dilegalisir\nPetugas memverifikasi keaslian dokumen\nDokumen diberikan stempel dan tanda tangan legalisir",
                'sop' => "Petugas mencocokkan salinan dengan dokumen asli\nKepala KUA/Petugas menandatangani legalisir\nLegalisir diserahkan kepada pemohon",
            ],
            [
                'name' => 'Isbat Nikah',
                'slug' => 'isbat-nikah',
                'icon' => 'document',
                'sort_order' => 5,
                'description' => 'Penetapan pengesahan nikah (isbat) bagi perkawinan yang belum tercatat sesuai ketentuan yang berlaku.',
                'persyaratan' => "Surat pengantar dari kelurahan/desa\nFoto copy KTP dan Kartu Keluarga (KK)\nDua orang saksi nikah\nSurat keterangan belum pernah tercatat pernikahan\nSurat permohonan isbat nikah",
                'alur' => "Pemohon berkonsultasi dan mendaftarkan isbat nikah ke KUA\nPetugas memverifikasi berkas permohonan\nSidang isbat nikah di pengadilan agama\nPencatatan pernikahan setelah ada penetapan pengadilan",
                'sop' => "Petugas memberikan informasi dan pendampingan pengajuan isbat\nBerkas permohonan diteliti kelengkapannya\nHasil penetapan pengadilan dicatat di KUA",
            ],
            [
                'name' => 'Duplikat Akta Nikah',
                'slug' => 'duplikat-akta-nikah',
                'icon' => 'document',
                'sort_order' => 6,
                'description' => 'Permohonan penggantian buku nikah/akta nikah yang hilang, rusak, atau tidak terbaca.',
                'persyaratan' => "Surat keterangan kehilangan dari kepolisian (jika hilang)\nFoto copy KTP dan Kartu Keluarga (KK)\nFoto copy akta nikah lama (jika rusak)\nPas foto terbaru",
                'alur' => "Ajukan permohonan melalui formulir online di bawah ini\nPetugas memproses dan menyetujui permohonan\nDatang ke KUA membawa berkas persyaratan asli\nDuplikat akta nikah dapat diambil atau diunduh",
                'sop' => "Petugas memeriksa data permohonan di sistem\nKepala KUA menyetujui penerbitan duplikat\nDuplikat akta nikah diterbitkan dan diserahkan",
            ],
            [
                'name' => 'Perubahan Akta Nikah',
                'slug' => 'perubahan-akta-nikah',
                'icon' => 'user',
                'sort_order' => 7,
                'description' => 'Permohonan perubahan data pada akta nikah, misalnya perbaikan nama, tempat, atau tanggal lahir.',
                'persyaratan' => "Akta nikah asli\nSalinan penetapan pengadilan (jika ada)\nFoto copy KTP dan Kartu Keluarga (KK)\nPas foto terbaru",
                'alur' => "Ajukan permohonan melalui formulir online di bawah ini\nPetugas memproses dan menyetujui permohonan\nDatang ke KUA membawa berkas persyaratan asli\nAkta nikah dengan data baru dapat diambil",
                'sop' => "Petugas memeriksa keabsahan data permohonan\nPerubahan data diproses sesuai ketentuan\nDokumen hasil perubahan diserahkan kepada pemohon",
            ],
            [
                'name' => 'Surat Keterangan Nikah',
                'slug' => 'keterangan-nikah',
                'icon' => 'check',
                'sort_order' => 8,
                'description' => 'Surat keterangan bahwa seseorang telah melangsungkan perkawinan untuk keperluan administrasi.',
                'persyaratan' => "Foto copy KTP pemohon\nFoto copy buku nikah (jika ada)\nPas foto terbaru",
                'alur' => "Ajukan permohonan melalui formulir online di bawah ini\nPetugas memproses dan menyetujui permohonan\nDatang ke KUA membawa berkas persyaratan asli\nSurat keterangan nikah dapat diunduh",
                'sop' => "Petugas meneliti data perkawinan pemohon\nSurat keterangan nikah diterbitkan dan ditandatangani\nSurat diserahkan kepada pemohon",
            ],
            [
                'name' => 'Pencatatan Nikah Luar Negeri',
                'slug' => 'pencatatan-nikah-luar-negeri',
                'icon' => 'envelope',
                'sort_order' => 9,
                'description' => 'Pencatatan perkawinan Warga Negara Indonesia yang dilangsungkan di luar negeri.',
                'persyaratan' => "Salinan akta perkawinan luar negeri yang dilegalisir\nFoto copy paspor yang masih berlaku\nFoto copy KTP dan Kartu Keluarga (KK)\nSurat pengantar dari perwakilan RI (jika dipersyaratkan)",
                'alur' => "Ajukan permohonan melalui formulir online di bawah ini\nPetugas memproses dan menyetujui permohonan\nDatang ke KUA membawa berkas persyaratan asli\nAkta nikah hasil pencatatan dapat diambil",
                'sop' => "Petugas memeriksa keabsahan dokumen perkawinan luar negeri\nPencatatan dilakukan sesuai ketentuan\nDokumen pencatatan diserahkan kepada pemohon",
            ],
            [
                'name' => 'Cari Akta',
                'slug' => 'cari-akta',
                'icon' => 'info',
                'sort_order' => 10,
                'description' => 'Pencarian dan pengecekan data akta/buku nikah yang tersimpan di KUA.',
                'persyaratan' => "Data nama lengkap pemilik akta\nTanggal perkawinan\nNomor akta/buku nikah (jika diketahui)",
                'alur' => "Klik tombol Cari Akta di bawah ini\nMasukkan data pada form pencarian yang tersedia\nHasil pencarian ditampilkan",
                'sop' => "Pemohon mengisi data pencarian pada sistem\nSistem menampilkan hasil pencarian akta\nPetugas membantu verifikasi lebih lanjut jika diperlukan",
            ],
        ];

        foreach ($topics as $topic) {
            MarriageService::firstOrCreate(
                ['slug' => $topic['slug']],
                $topic,
            );
        }
    }
}
