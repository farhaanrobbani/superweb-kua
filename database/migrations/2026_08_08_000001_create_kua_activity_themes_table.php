<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kua_activity_themes', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('label', 255);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $themes = [
            'pendaftaran_nikah_kantor' => 'Pendaftaran Nikah di Kantor',
            'pendaftaran_nikah_luar_kantor' => 'Pendaftaran Nikah di Luar Kantor',
            'pelaksanaan_nikah_kantor' => 'Pelaksanaan Nikah di Kantor',
            'pelaksanaan_nikah_luar_kantor' => 'Pelaksanaan Nikah di Luar Kantor',
            'pelaksanaan_bimwin' => 'Pelaksanaan Bimbingan Perkawinan (Bimwin)',
            'duplikat_buku_nikah' => 'Pelayanan Duplikat Buku Nikah',
            'surat_rekomendasi_nikah' => 'Penerbitan Surat Rekomendasi Nikah',
            'legalisir_buku_nikah' => 'Pelayanan Legalisir Buku Nikah',
            'surat_keluar' => 'Pengelolaan & Pengiriman Surat Keluar',
            'pelaksanaan_wakaf' => 'Pelaksanaan & Pelayanan Akta Wakaf',
        ];

        foreach ($themes as $key => $label) {
            DB::table('kua_activity_themes')->insert([
                'key' => $key,
                'label' => $label,
                'active' => true,
                'sort_order' => array_search($key, array_keys($themes)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kua_activity_themes');
    }
};
