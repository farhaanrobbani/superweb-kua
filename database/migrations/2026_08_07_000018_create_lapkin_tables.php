<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kua_daily_data', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->unsignedInteger('pendaftaran_nikah_kantor')->default(0);
            $table->unsignedInteger('pendaftaran_nikah_luar_kantor')->default(0);
            $table->unsignedInteger('pelaksanaan_nikah_kantor')->default(0);
            $table->unsignedInteger('pelaksanaan_nikah_luar_kantor')->default(0);
            $table->unsignedInteger('pelaksanaan_bimwin')->default(0);
            $table->unsignedInteger('duplikat_buku_nikah')->default(0);
            $table->unsignedInteger('surat_rekomendasi_nikah')->default(0);
            $table->unsignedInteger('legalisir_buku_nikah')->default(0);
            $table->unsignedInteger('surat_keluar')->default(0);
            $table->unsignedInteger('pelaksanaan_wakaf')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('staff_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('pekerjaan');
            $table->string('activity_type_key', 100)->nullable();
            $table->unsignedInteger('total_jumlah')->default(1);
            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
            $table->index('activity_type_key');
        });

        Schema::create('user_activity_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('activity_type_key', 100);
            $table->text('kegiatan');
            $table->text('pekerjaan');
            $table->timestamps();

            $table->unique(['user_id', 'activity_type_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activity_templates');
        Schema::dropIfExists('staff_activities');
        Schema::dropIfExists('kua_daily_data');
    }
};
