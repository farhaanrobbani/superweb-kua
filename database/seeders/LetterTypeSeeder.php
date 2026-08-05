<?php

namespace Database\Seeders;

use App\Models\LetterTemplate;
use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code' => 'SPN',
                'name' => 'Surat Pengantar Nikah',
                'description' => 'Pengantar untuk pencatatan perkawinan di KUA.',
                'fields' => [
                    ['name' => 'nama_suami', 'label' => 'Nama Calon Suami', 'type' => 'text', 'required' => true],
                    ['name' => 'ttl_suami', 'label' => 'Tempat, Tgl Lahir Suami', 'type' => 'text', 'required' => true],
                    ['name' => 'nama_istri', 'label' => 'Nama Calon Istri', 'type' => 'text', 'required' => true],
                    ['name' => 'ttl_istri', 'label' => 'Tempat, Tgl Lahir Istri', 'type' => 'text', 'required' => true],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                    ['name' => 'tempat_nikah', 'label' => 'Tempat Akad Nikah', 'type' => 'text', 'required' => false],
                    ['name' => 'tanggal_nikah', 'label' => 'Tanggal Akad Nikah', 'type' => 'date', 'required' => false],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Pengantar Nikah',
                        'body' => "Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] Kabupaten [kabupaten], menerangkan bahwa:\n\nNama Calon Suami : [nama_suami]\nTempat, Tgl Lahir : [ttl_suami]\n\nNama Calon Istri : [nama_istri]\nTempat, Tgl Lahir : [ttl_istri]\n\nAlamat : [alamat]\n\nKedua orang tersebut di atas bermaksud melangsungkan akad nikah yang insya Allah dilaksanakan pada tanggal [tanggal_nikah] bertempat di [tempat_nikah].\n\nSehubungan dengan hal tersebut, kami memberikan pengantar agar kedua calon mempelai dapat melaksanakan proses pencatatan perkawinan sesuai ketentuan yang berlaku.",
                    ],
                ],
            ],
            [
                'code' => 'SKU',
                'name' => 'Surat Keterangan (Umum)',
                'description' => 'Surat keterangan untuk keperluan umum (belum menikah, domisili, dll).',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'ttl', 'label' => 'Tempat, Tgl Lahir', 'type' => 'text', 'required' => true],
                    ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => false],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'select', 'required' => true, 'options' => ['Keterangan Belum Menikah', 'Keterangan Domisili', 'Keterangan untuk Beasiswa', 'Keterangan untuk Melamar Kerja', 'Keterangan lainnya']],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Keterangan Umum',
                        'body' => "Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] Kabupaten [kabupaten], dengan ini menerangkan bahwa:\n\nNama : [nama]\nTempat, Tgl Lahir : [ttl]\nNIK : [nik]\nAlamat : [alamat]\n\nAdalah benar yang bersangkutan merupakan warga di wilayah Kecamatan [kecamatan] dan beragama Islam. Surat keterangan ini dibuat untuk keperluan [keperluan].\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
                    ],
                ],
            ],
            [
                'code' => 'SPC',
                'name' => 'Surat Pengantar Cerai/Talak',
                'description' => 'Pengantar permohonan cerai/talak ke Pengadilan Agama.',
                'fields' => [
                    ['name' => 'nama_suami', 'label' => 'Nama Suami', 'type' => 'text', 'required' => true],
                    ['name' => 'nama_istri', 'label' => 'Nama Istri', 'type' => 'text', 'required' => true],
                    ['name' => 'tempat_nikah', 'label' => 'Tempat Nikah', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_nikah', 'label' => 'Tanggal Nikah', 'type' => 'date', 'required' => true],
                    ['name' => 'alasan', 'label' => 'Alasan Permohonan', 'type' => 'select', 'required' => true, 'options' => ['Perselisihan terus-menerus', 'Ekonomi', 'Kekerasan dalam rumah tangga', 'Alasan lainnya']],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Pengantar Cerai/Talak',
                        'body' => "Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] Kabupaten [kabupaten], menerangkan bahwa:\n\nNama Suami : [nama_suami]\nNama Istri : [nama_istri]\n\nKedua orang tersebut telah menikah pada tanggal [tanggal_nikah] di [tempat_nikah] dan telah tercatat di KUA Kecamatan [kecamatan].\n\nSaat ini pasangan tersebut bermaksud mengajukan permohonan cerai/talak dengan alasan [alasan]. Surat pengantar ini dibuat sebagai kelengkapan permohonan ke Pengadilan Agama.",
                    ],
                ],
            ],
            [
                'code' => 'SUP',
                'name' => 'Surat Undangan/Pemberitahuan',
                'description' => 'Undangan kegiatan atau pemberitahuan resmi.',
                'fields' => [
                    ['name' => 'nama_undangan', 'label' => 'Nama Undangan', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_acara', 'label' => 'Tanggal Acara', 'type' => 'date', 'required' => true],
                    ['name' => 'waktu_acara', 'label' => 'Waktu Acara', 'type' => 'text', 'required' => true],
                    ['name' => 'tempat_acara', 'label' => 'Tempat Acara', 'type' => 'text', 'required' => true],
                    ['name' => 'acara', 'label' => 'Nama Acara', 'type' => 'text', 'required' => true],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Undangan',
                        'body' => "Dalam rangka acara [acara], kami mengundang Saudara untuk hadir pada:\n\nHari, Tanggal : [tanggal_acara]\nWaktu : [waktu_acara]\nTempat : [tempat_acara]\n\nDemikian undangan ini disampaikan. Atas perhatian dan kehadiran Saudara, kami ucapkan terima kasih.",
                    ],
                ],
            ],
            [
                'code' => 'SIN',
                'name' => 'Surat Internal/Administrasi',
                'description' => 'Nota dinas, surat tugas, dan surat administrasi internal KUA.',
                'fields' => [
                    ['name' => 'nama_pegawai', 'label' => 'Nama Pegawai', 'type' => 'text', 'required' => true],
                    ['name' => 'nip', 'label' => 'NIP', 'type' => 'text', 'required' => false],
                    ['name' => 'jenis_internal', 'label' => 'Jenis Surat', 'type' => 'select', 'required' => true, 'options' => ['Nota Dinas', 'Surat Tugas', 'Surat Perintah']],
                    ['name' => 'uraian', 'label' => 'Uraian Tugas', 'type' => 'textarea', 'required' => true],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Internal',
                        'body' => "Berdasarkan surat perintah dari Kepala Kantor Urusan Agama Kecamatan [kecamatan], kami menugaskan:\n\nNama : [nama_pegawai]\nNIP : [nip]\n\nUntuk melaksanakan tugas sebagai berikut:\n[uraian]\n\nDemikian surat ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.",
                    ],
                ],
            ],
            [
                'code' => 'SP',
                'name' => 'Surat Pengantar',
                'description' => 'Pengantar untuk keperluan SKCK, domisili, beasiswa, dan lainnya.',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'ttl', 'label' => 'Tempat, Tgl Lahir', 'type' => 'text', 'required' => true],
                    ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => false],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'select', 'required' => true, 'options' => ['Pengantar SKCK', 'Pengantar Domisili', 'Pengantar Beasiswa', 'Pengantar Melamar Kerja', 'Pengantar lainnya']],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Pengantar',
                        'body' => "Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] Kabupaten [kabupaten], menerangkan bahwa:\n\nNama : [nama]\nTempat, Tgl Lahir : [ttl]\nNIK : [nik]\nAlamat : [alamat]\n\nYang bersangkutan adalah warga di wilayah Kecamatan [kecamatan] dan beragama Islam. Surat pengantar ini dibuat untuk keperluan [keperluan].\n\nDemikian surat pengantar ini dibuat untuk dipergunakan sebagaimana mestinya.",
                    ],
                ],
            ],
            [
                'code' => 'SPD',
                'name' => 'Surat Permohonan Duplikat Akta Nikah',
                'description' => 'Permohonan penggantian akta nikah yang hilang atau rusak.',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'no_akta', 'label' => 'Nomor Akta Nikah', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_akta', 'label' => 'Tanggal Akta Nikah', 'type' => 'date', 'required' => true],
                    ['name' => 'tempat_akad', 'label' => 'Tempat Akad Nikah', 'type' => 'text', 'required' => true],
                    ['name' => 'alasan', 'label' => 'Alasan Permohonan', 'type' => 'select', 'required' => true, 'options' => ['Akta hilang', 'Akta rusak', 'Keperluan lainnya']],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Permohonan Duplikat Akta Nikah',
                        'body' => "Yang bertanda tangan di bawah ini:\n\nNama : [nama]\nNomor Akta : [no_akta]\nTanggal Akta : [tanggal_akta]\nTempat Akad : [tempat_akad]\n\nDengan ini mengajukan permohonan duplikat Akta Nikah karena [alasan].\n\nDemikian permohonan ini kami sampaikan untuk dapat diproses sebagaimana mestinya.",
                    ],
                ],
            ],
            [
                'code' => 'SPA',
                'name' => 'Surat Permohonan Perubahan Akta',
                'description' => 'Permohonan perbaikan/perubahan data pada akta nikah.',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'no_akta', 'label' => 'Nomor Akta Nikah', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggal_akta', 'label' => 'Tanggal Akta Nikah', 'type' => 'date', 'required' => true],
                    ['name' => 'jenis_perubahan', 'label' => 'Jenis Perubahan', 'type' => 'select', 'required' => true, 'options' => ['Perubahan nama', 'Perubahan tanggal/tempat lahir', 'Perbaikan penulisan data', 'Perubahan data lainnya']],
                    ['name' => 'uraian', 'label' => 'Uraian Perubahan', 'type' => 'textarea', 'required' => true],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Permohonan Perubahan Akta',
                        'body' => "Yang bertanda tangan di bawah ini:\n\nNama : [nama]\nNomor Akta : [no_akta]\nTanggal Akta : [tanggal_akta]\n\nDengan ini mengajukan permohonan perubahan data pada Akta Nikah, yaitu [jenis_perubahan], dengan uraian sebagai berikut:\n[uraian]\n\nDemikian permohonan ini kami sampaikan untuk dapat diproses sebagaimana mestinya.",
                    ],
                ],
            ],
            [
                'code' => 'SPM',
                'name' => 'Surat Permohonan (Umum)',
                'description' => 'Permohonan keterangan atau layanan lain secara umum.',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                    ['name' => 'ttl', 'label' => 'Tempat, Tgl Lahir', 'type' => 'text', 'required' => true],
                    ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => false],
                    ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                    ['name' => 'perihal', 'label' => 'Perihal Permohonan', 'type' => 'textarea', 'required' => true],
                ],
                'templates' => [
                    [
                        'name' => 'Template Surat Permohonan Umum',
                        'body' => "Yang bertanda tangan di bawah ini:\n\nNama : [nama]\nTempat, Tgl Lahir : [ttl]\nNIK : [nik]\nAlamat : [alamat]\n\nDengan ini mengajukan permohonan sebagai berikut:\n[perihal]\n\nDemikian permohonan ini kami sampaikan untuk dapat diproses sebagaimana mestinya.",
                    ],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $templates = $typeData['templates'];
            unset($typeData['templates']);

            $type = LetterType::updateOrCreate(
                ['code' => $typeData['code']],
                $typeData
            );

            foreach ($templates as $template) {
                LetterTemplate::updateOrCreate(
                    ['letter_type_id' => $type->id, 'name' => $template['name']],
                    $template
                );
            }
        }
    }
}
