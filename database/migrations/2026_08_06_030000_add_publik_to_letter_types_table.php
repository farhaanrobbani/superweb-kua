<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const NEW_TYPES = [
        [
            'code' => 'SKN',
            'name' => 'Surat Keterangan Nikah',
            'description' => 'Keterangan telah menikah untuk keperluan tunjangan, asuransi, BPJS, dan lainnya.',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                ['name' => 'ttl', 'label' => 'Tempat, Tgl Lahir', 'type' => 'text', 'required' => true],
                ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => false],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
                ['name' => 'nama_pasangan', 'label' => 'Nama Suami/Istri', 'type' => 'text', 'required' => true],
                ['name' => 'tempat_nikah', 'label' => 'Tempat Nikah', 'type' => 'text', 'required' => true],
                ['name' => 'tanggal_nikah', 'label' => 'Tanggal Nikah', 'type' => 'date', 'required' => true],
                ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'select', 'required' => true, 'options' => ['Tunjangan keluarga', 'Asuransi', 'BPJS Kesehatan', 'Keperluan lainnya']],
            ],
            'templates' => [
                [
                    'name' => 'Template Surat Keterangan Nikah',
                    'body' => "Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] Kabupaten [kabupaten], dengan ini menerangkan bahwa:\n\nNama : [nama]\nTempat, Tgl Lahir : [ttl]\nNIK : [nik]\nAlamat : [alamat]\n\nBenar yang bersangkutan telah melangsungkan pernikahan dengan [nama_pasangan] pada tanggal [tanggal_nikah] di [tempat_nikah] dan telah tercatat di Kantor Urusan Agama.\n\nSurat keterangan ini dibuat untuk keperluan [keperluan].\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
                ],
            ],
        ],
        [
            'code' => 'PNL',
            'name' => 'Surat Permohonan Pencatatan Nikah Luar Negeri',
            'description' => 'Permohonan pencatatan perkawinan Warga Negara Indonesia yang dilangsungkan di luar negeri.',
            'fields' => [
                ['name' => 'nama_suami', 'label' => 'Nama Suami', 'type' => 'text', 'required' => true],
                ['name' => 'ttl_suami', 'label' => 'Tempat, Tgl Lahir Suami', 'type' => 'text', 'required' => true],
                ['name' => 'nama_istri', 'label' => 'Nama Istri', 'type' => 'text', 'required' => true],
                ['name' => 'ttl_istri', 'label' => 'Tempat, Tgl Lahir Istri', 'type' => 'text', 'required' => true],
                ['name' => 'tempat_nikah', 'label' => 'Tempat Akad Nikah', 'type' => 'text', 'required' => true],
                ['name' => 'tanggal_nikah', 'label' => 'Tanggal Akad Nikah', 'type' => 'date', 'required' => true],
                ['name' => 'negara', 'label' => 'Negara Tempat Nikah', 'type' => 'text', 'required' => true],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true],
            ],
            'templates' => [
                [
                    'name' => 'Template Surat Permohonan Pencatatan Nikah Luar Negeri',
                    'body' => "Yang bertanda tangan di bawah ini:\n\nNama Suami : [nama_suami]\nTempat, Tgl Lahir : [ttl_suami]\n\nNama Istri : [nama_istri]\nTempat, Tgl Lahir : [ttl_istri]\n\nAlamat : [alamat]\n\nDengan ini mengajukan permohonan pencatatan perkawinan yang telah dilangsungkan pada tanggal [tanggal_nikah] di [tempat_nikah], [negara], sesuai ketentuan pencatatan perkawinan bagi Warga Negara Indonesia di luar negeri.\n\nDemikian permohonan ini kami sampaikan untuk dapat diproses sebagaimana mestinya.",
                ],
            ],
        ],
    ];

    public function up(): void
    {
        Schema::table('letter_types', function (Blueprint $table) {
            $table->boolean('publik')->default(false)->after('active');
        });

        DB::table('letter_types')
            ->whereIn('code', ['SPD', 'SPA'])
            ->update(['publik' => true]);

        foreach (self::NEW_TYPES as $typeData) {
            $templates = $typeData['templates'];
            unset($typeData['templates']);
            $typeData['publik'] = true;

            $typeId = DB::table('letter_types')->insertGetId(array_merge($typeData, [
                'fields' => json_encode($typeData['fields']),
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            foreach ($templates as $template) {
                DB::table('letter_templates')->insert(array_merge($template, [
                    'letter_type_id' => $typeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        foreach (self::NEW_TYPES as $typeData) {
            $type = DB::table('letter_types')->where('code', $typeData['code'])->first();
            if ($type) {
                DB::table('letter_templates')->where('letter_type_id', $type->id)->delete();
                DB::table('letter_types')->where('id', $type->id)->delete();
            }
        }

        Schema::table('letter_types', function (Blueprint $table) {
            $table->dropColumn('publik');
        });
    }
};
