<?php

namespace Database\Seeders;

use App\Models\ReligiousService;
use Illuminate\Database\Seeder;

class ReligiousServiceSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Pembinaan Majelis Taklim',
                'slug' => 'pembinaan-majelis-taklim',
                'icon' => 'users',
                'sort_order' => 1,
                'description' => 'Pendataan, pembinaan, dan pemberdayaan majelis taklim di wilayah KUA.',
                'persyaratan' => "Data nama majelis taklim dan alamat\nDokumen pendirian/legalitas majelis taklim (jika ada)\nData pengurus (ketua, sekretaris, bendahara)\nJadwal kegiatan rutin majelis taklim",
                'alur' => "Majelis taklim melaporkan data ke KUA\nPetugas melakukan verifikasi dan pendataan\nPembinaan dan pendampingan dijadwalkan sesuai kebutuhan\nPelaporan perkembangan pembinaan dilakukan secara berkala",
                'sop' => "Petugas menerima laporan data majelis taklim\nPetugas melakukan verifikasi kelengkapan dan kebenaran data\nPembinaan dilaksanakan melalui kegiatan rutin atau penyuluhan\nHasil pembinaan dicatat dan dilaporkan kepada pimpinan",
            ],
            [
                'name' => 'Khotbah & Pengajian Rutin',
                'slug' => 'khotbah-pengajian-rutin',
                'icon' => 'calendar',
                'sort_order' => 2,
                'description' => 'Penjadwalan khatib dan penceramah untuk kegiatan khotbah serta pengajian rutin.',
                'persyaratan' => "Permohonan penjadwalan khatib/penceramah dari masjid atau pengurus\nData nama khatib/penceramah yang diusulkan\nWaktu dan tempat pelaksanaan kegiatan",
                'alur' => "Panitia mengajukan permohonan penjadwalan\nKUA menyusun jadwal khatib/penceramah\nJadwal disampaikan kepada panitia\nPelaksanaan kegiatan dan evaluasi",
                'sop' => "Petugas menerima permohonan penjadwalan\nPetugas menyusun dan memfinalkan jadwal\nJadwal disampaikan kepada pihak terkait\nPetugas mencatat pelaksanaan kegiatan",
            ],
            [
                'name' => 'Penyuluhan Agama',
                'slug' => 'penyuluhan-agama',
                'icon' => 'info',
                'sort_order' => 3,
                'description' => 'Penyuluhan keagamaan Islam kepada masyarakat, termasuk program keluarga sakinah dan moderasi beragama.',
                'persyaratan' => "Surat permohonan kegiatan penyuluhan dari lembaga/kelompok masyarakat\nData sasaran peserta penyuluhan\nUsulan tema dan materi penyuluhan",
                'alur' => "Kelompok masyarakat mengajukan permohonan penyuluhan\nKUA menetapkan jadwal, materi, dan narasumber\nPenyuluhan dilaksanakan sesuai jadwal\nPelaporan hasil kegiatan",
                'sop' => "Petugas menerima dan menelaah permohonan penyuluhan\nPetugas menyiapkan narasumber dan materi\nKegiatan penyuluhan dilaksanakan\nHasil penyuluhan dievaluasi dan dilaporkan",
            ],
            [
                'name' => 'Penasehatan Keluarga',
                'slug' => 'penasehatan-keluarga',
                'icon' => 'home',
                'sort_order' => 4,
                'description' => 'Konsultasi dan pendampingan keluarga: pranikah, pasca-nikah, hingga sengketa rumah tangga.',
                'persyaratan' => "Identitas diri pasangan/pihak yang berkonsultasi\nPokok permasalahan yang akan dikonsultasikan\nDokumen pendukung (jika ada)",
                'alur' => "Pihak yang membutuhkan datang atau menghubungi KUA\nPetugas menjadwalkan sesi konsultasi\nSesi penasehatan dilaksanakan secara terbuka atau tertutup\nTindak lanjut dan pendampingan lanjutan",
                'sop' => "Petugas menerima dan mencatat permintaan konsultasi\nPetugas menjadwalkan sesi penasehatan\nPetugas melaksanakan penasehatan sesuai ketentuan\nHasil konsultasi dicatat dan dirahasiakan",
            ],
            [
                'name' => 'Bimbingan Perkawinan (Binwin)',
                'slug' => 'bimbingan-perkawinan',
                'icon' => 'heart',
                'sort_order' => 5,
                'description' => 'Bimbingan perkawinan bagi calon pengantin dan pasangan suami istri untuk membangun keluarga sakinah.',
                'persyaratan' => "Surat pengantar nikah dari KUA/desa\nFotokopi KTP calon pengantin\nFotokopi kartu keluarga\nPas foto sesuai ketentuan",
                'alur' => "Calon pengantin mendaftar bimbingan perkawinan\nPetugas memverifikasi berkas pendaftaran\nBimbingan perkawinan dilaksanakan sesuai jadwal\nSertifikat bimbingan diterbitkan",
                'sop' => "Petugas menerima pendaftaran bimbingan perkawinan\nPetugas memverifikasi kelengkapan berkas\nBimbingan perkawinan dilaksanakan oleh narasumber yang berkompeten\nSertifikat diserahkan kepada peserta",
            ],
            [
                'name' => 'Pembinaan Mubaligh & Penyuluh',
                'slug' => 'pembinaan-mubaligh-penyuluh',
                'icon' => 'user',
                'sort_order' => 6,
                'description' => 'Pendataan dan pembinaan mubaligh serta penyuluh agama di wilayah KUA.',
                'persyaratan' => "Data diri mubaligh/penyuluh (nama, alamat, kompetensi)\nSurat keterangan aktif membina dari lembaga\nJadwal kegiatan dakwah/kepenyuluhan",
                'alur' => "Pendataan mubaligh/penyuluh dilakukan oleh KUA\nVerifikasi kompetensi dan keaktifan\nPembinaan dilaksanakan melalui kegiatan rutin\nPelaporan hasil pembinaan",
                'sop' => "Petugas mengumpulkan data mubaligh/penyuluh\nPetugas memverifikasi keabsahan data\nPembinaan dilaksanakan sesuai kebutuhan\nData dan hasil pembinaan diperbarui berkala",
            ],
            [
                'name' => 'Fardhu Kifayah & Amil Zakat',
                'slug' => 'fardhu-kifayah-amil-zakat',
                'icon' => 'check',
                'sort_order' => 7,
                'description' => 'Pendampingan pengelolaan zakat, infak, dan sedekah (ZIS) serta koordinasi kegiatan fardhu kifayah.',
                'persyaratan' => "Permohonan pendampingan dari pengurus/amil zakat\nData pengurus dan program pengelolaan ZIS\nLaporan pengelolaan ZIS (jika ada)",
                'alur' => "Pengurus/amil zakat mengajukan pendampingan\nKUA memverifikasi dan menyusun program pendampingan\nPendampingan dilaksanakan secara berkala\nLaporan pendampingan disampaikan",
                'sop' => "Petugas menerima permohonan pendampingan\nPetugas menyusun program pendampingan ZIS\nKegiatan pendampingan dilaksanakan\nHasil pendampingan dievaluasi",
            ],
            [
                'name' => 'Kampung Moderasi Beragama',
                'slug' => 'kampung-moderasi-beragama',
                'icon' => 'users',
                'sort_order' => 8,
                'description' => 'Fasilitasi program kampung moderasi beragama di wilayah kerja KUA.',
                'persyaratan' => "Penetapan lokasi kampung moderasi beragama\nData kelompok masyarakat dan tokoh agama\nProgram kegiatan yang diusulkan",
                'alur' => "Penetapan dan sosialisasi kampung moderasi beragama\nPenyusunan program bersama tokoh masyarakat\nPelaksanaan kegiatan moderasi beragama\nEvaluasi dan pelaporan",
                'sop' => "Petugas memfasilitasi penetapan lokasi kampung\nPetugas mendampingi penyusunan program\nKegiatan dilaksanakan bersama masyarakat\nHasil kegiatan dievaluasi dan dilaporkan",
            ],
            [
                'name' => 'Kerukunan Umat Beragama',
                'slug' => 'kerukunan-umat-beragama',
                'icon' => 'heart',
                'sort_order' => 9,
                'description' => 'Pembinaan kerukunan dan toleransi antarumat beragama di wilayah KUA.',
                'persyaratan' => "Usulan kegiatan kerukunan umat beragama\nData peserta/lembaga yang terlibat\nWaktu dan tempat pelaksanaan",
                'alur' => "Usulan kegiatan disampaikan ke KUA\nKoordinasi dengan tokoh dan lembaga lintas agama\nPelaksanaan kegiatan kerukunan\nPelaporan hasil kegiatan",
                'sop' => "Petugas menerima usulan kegiatan\nPetugas berkoordinasi dengan pihak terkait\nKegiatan dilaksanakan sesuai rencana\nHasil kegiatan dilaporkan",
            ],
        ];

        foreach ($topics as $topic) {
            ReligiousService::firstOrCreate(
                ['slug' => $topic['slug']],
                $topic,
            );
        }
    }
}
