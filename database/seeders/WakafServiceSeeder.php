<?php

namespace Database\Seeders;

use App\Models\WakafService;
use Illuminate\Database\Seeder;

class WakafServiceSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Pendaftaran Akta Ikrar Wakaf (AIW)',
                'slug' => 'pendaftaran-akta-ikrar-wakaf',
                'icon' => 'document',
                'sort_order' => 1,
                'description' => 'Penerbitan Akta Ikrar Wakaf (AIW) untuk penyerahan harta wakaf oleh wakif.',
                'persyaratan' => "Surat permohonan pengajuan wakaf\nIdentitas wakif (KTP/KK)\nIdentitas nadzir yang ditunjuk\nSurat kepemilikan/legalitas harta wakaf\nData saksi ikrar wakaf",
                'alur' => "Wakif mengajukan permohonan wakaf ke KUA\nPetugas memverifikasi kelengkapan berkas\nIkrar wakaf dilaksanakan di hadapan Pejabat Pembuat Akta Ikrar Wakaf (PPAIW)\nAkta Ikrar Wakaf (AIW) diterbitkan dan diserahkan",
                'sop' => "Petugas menerima dan menelaah berkas permohonan wakaf\nPetugas menjadwalkan ikrar wakaf bersama PPAIW\nIkrar wakaf dilaksanakan sesuai ketentuan syariah dan peraturan\nAIW ditandatangani dan didaftarkan",
            ],
            [
                'name' => 'Pendaftaran Nadzir',
                'slug' => 'pendaftaran-nadzir',
                'icon' => 'user',
                'sort_order' => 2,
                'description' => 'Pendaftaran dan legalisasi nadzir perorangan, organisasi, atau lembaga.',
                'persyaratan' => "Surat permohonan pendaftaran nadzir\nIdentitas dan data diri nadzir\nSurat pernyataan kesediaan menjadi nadzir\nDokumen legalitas organisasi/lembaga (jika nadzir lembaga)",
                'alur' => "Calon nadzir mengajukan permohonan pendaftaran\nKUA memverifikasi kelengkapan dan kompetensi\nNadzir didaftarkan dan ditetapkan\nData nadzir dicatat dalam register wakaf",
                'sop' => "Petugas menerima permohonan pendaftaran nadzir\nPetugas melakukan verifikasi data dan legalitas\nNadzir ditetapkan dan didaftarkan oleh pejabat berwenang\nData diperbarui dalam administrasi wakaf",
            ],
            [
                'name' => 'Penggantian Nadzir',
                'slug' => 'penggantian-nadzir',
                'icon' => 'users',
                'sort_order' => 3,
                'description' => 'Penggantian nadzir yang berhenti, berhalangan tetap, atau melanggar ketentuan.',
                'persyaratan' => "Surat permohonan penggantian nadzir\nAlasan penggantian (berhenti/berhalangan/melanggar)\nUsulan nadzir pengganti beserta data diri\nBerita acara rapat pengurus (jika nadzir lembaga)",
                'alur' => "Pengajuan usulan penggantian nadzir ke KUA\nVerifikasi alasan dan kelengkapan berkas\nPenetapan nadzir pengganti\nPencatatan dan pelaporan penggantian nadzir",
                'sop' => "Petugas menerima usulan penggantian nadzir\nPetugas memverifikasi alasan dan kelengkapan dokumen\nNadzir pengganti ditetapkan sesuai ketentuan\nData wakaf diperbarui dan dilaporkan",
            ],
            [
                'name' => 'Perubahan Data Wakaf',
                'slug' => 'perubahan-data-wakaf',
                'icon' => 'envelope',
                'sort_order' => 4,
                'description' => 'Pelayanan perubahan/perbaikan data harta wakaf pada sertifikat atau register.',
                'persyaratan' => "Surat permohonan perubahan data wakaf\nAkta Ikrar Wakaf dan dokumen kepemilikan\nIdentitas wakif dan nadzir\nBukti perubahan data (mis. perubahan nama/status)",
                'alur' => "Nadzir mengajukan permohonan perubahan data\nVerifikasi dan validasi data oleh petugas\nPenerbitan data/rekomendasi perubahan\nPencatatan perubahan pada register wakaf",
                'sop' => "Petugas menerima permohonan perubahan data\nPetugas memverifikasi kebenaran data dan dokumen\nPerubahan diproses sesuai ketentuan\nData register wakaf diperbarui",
            ],
            [
                'name' => 'Sertifikasi Tanah Wakaf',
                'slug' => 'sertifikasi-tanah-wakaf',
                'icon' => 'home',
                'sort_order' => 5,
                'description' => 'Fasilitasi pensertifikatan dan pengamanan aset tanah wakaf di wilayah KUA.',
                'persyaratan' => "Surat permohonan sertifikasi tanah wakaf\nSertifikat/dokumen kepemilikan tanah\nAkta Ikrar Wakaf (jika sudah ada)\nData wakif dan nadzir\nPeta bidang/pengukuran tanah (jika diminta)",
                'alur' => "Nadzir mengajukan permohonan sertifikasi\nKUA berkoordinasi dengan BPN untuk pengukuran dan pensertifikatan\nPenerbitan sertifikat atas nama nadzir\nPenyerahan dan pencatatan sertifikat",
                'sop' => "Petugas menerima permohonan sertifikasi tanah wakaf\nPetugas berkoordinasi dengan BPN dan pihak terkait\nProses pensertifikatan dilaksanakan sesuai ketentuan\nSertifikat diserahkan dan dicatat",
            ],
            [
                'name' => 'Wakaf Uang',
                'slug' => 'wakaf-uang',
                'icon' => 'check',
                'sort_order' => 6,
                'description' => 'Pelayanan wakaf uang melalui Lembaga Keuangan Syariah Penerima Wakaf Uang (LKS-PWU).',
                'persyaratan' => "Identitas wakif (KTP/KK)\nNomor rekening/nomor pokok wakif\nBukti setoran wakaf uang\nPernyataan ikrar wakaf uang",
                'alur' => "Wakif menyetorkan wakaf uang melalui LKS-PWU\nKUA memverifikasi dan menerbitkan bukti wakaf uang\nDana dikelola dan disalurkan sesuai ketentuan\nPelaporan penyaluran wakaf uang",
                'sop' => "Petugas menerima permohonan wakaf uang\nPetugas memverifikasi setoran dan identitas wakif\nSertifikat/bukti wakaf uang diterbitkan\nPenyaluran dan pelaporan dilakukan sesuai ketentuan",
            ],
            [
                'name' => 'Wakaf Produktif',
                'slug' => 'wakaf-produktif',
                'icon' => 'info',
                'sort_order' => 7,
                'description' => 'Pendampingan pengelolaan harta wakaf produktif agar memberikan manfaat berkelanjutan.',
                'persyaratan' => "Proposal pengelolaan wakaf produktif\nData aset wakaf yang akan dikelola\nData nadzir dan rencana program\nLaporan pengelolaan sebelumnya (jika ada)",
                'alur' => "Nadzir mengajukan rencana pengelolaan wakaf produktif\nKUA memverifikasi dan memberikan pendampingan\nPengelolaan aset wakaf dilaksanakan sesuai rencana\nPelaporan hasil pengelolaan secara berkala",
                'sop' => "Petugas menerima rencana pengelolaan wakaf produktif\nPetugas melakukan pendampingan dan pembinaan nadzir\nPengelolaan aset dilaksanakan sesuai ketentuan\nHasil pengelolaan dievaluasi dan dilaporkan",
            ],
            [
                'name' => 'Pelaporan Nadzir',
                'slug' => 'pelaporan-nadzir',
                'icon' => 'calendar',
                'sort_order' => 8,
                'description' => 'Penerimaan dan verifikasi laporan berkala pengelolaan harta wakaf oleh nadzir.',
                'persyaratan' => "Laporan pengelolaan harta wakaf berkala\nData aset wakaf yang dikelola\nBukti penggunaan/pemanfaatan hasil wakaf\nRencana pengelolaan selanjutnya",
                'alur' => "Nadzir menyampaikan laporan berkala ke KUA\nPetugas memverifikasi kelengkapan laporan\nLaporan dicatat dan dievaluasi\nSaran tindak lanjut disampaikan kepada nadzir",
                'sop' => "Petugas menerima laporan nadzir\nPetugas memverifikasi kelengkapan dan kesesuaian laporan\nHasil evaluasi dicatat dalam administrasi wakaf\nTindak lanjut disampaikan kepada nadzir",
            ],
            [
                'name' => 'Konsultasi Wakaf',
                'slug' => 'konsultasi-wakaf',
                'icon' => 'heart',
                'sort_order' => 9,
                'description' => 'Konsultasi seputar tata cara, ketentuan, dan administrasi perwakafan di KUA.',
                'persyaratan' => "Identitas calon wakif/nadzir\nPokok pertanyaan atau permasalahan yang dikonsultasikan\nDokumen pendukung (jika ada)",
                'alur' => "Masyarakat datang atau menghubungi KUA\nPetugas menjadwalkan sesi konsultasi\nKonsultasi dan penjelasan dilaksanakan\nTindak lanjut dan pendampingan lanjutan",
                'sop' => "Petugas menerima dan mencatat permintaan konsultasi\nPetugas memberikan penjelasan sesuai peraturan perwakafan\nHasil konsultasi dicatat\nPendampingan lanjutan dilakukan bila diperlukan",
            ],
        ];

        foreach ($topics as $topic) {
            WakafService::firstOrCreate(
                ['slug' => $topic['slug']],
                $topic,
            );
        }
    }
}
