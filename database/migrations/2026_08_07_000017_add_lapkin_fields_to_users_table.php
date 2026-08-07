<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 50)->nullable()->after('email');
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('level_jabatan')->nullable()->after('jabatan');
            $table->string('pangkat')->nullable()->after('level_jabatan');
            $table->string('ruang_golongan', 50)->nullable()->after('pangkat');
            $table->unsignedInteger('grade_tukin')->default(8)->after('ruang_golongan');
            $table->decimal('jumlah_tukin_kotor', 15, 2)->default(0)->after('grade_tukin');
            $table->decimal('jumlah_tukin_bersih', 15, 2)->default(0)->after('jumlah_tukin_kotor');
            $table->decimal('gapok', 15, 2)->default(0)->after('jumlah_tukin_bersih');
            $table->decimal('jumlah_uang_makan_harian', 15, 2)->default(35150)->after('gapok');
            $table->string('foto_profil_url')->nullable()->after('jumlah_uang_makan_harian');
            $table->string('instansi')->default('KUA Ampelgading')->after('foto_profil_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'jabatan',
                'level_jabatan',
                'pangkat',
                'ruang_golongan',
                'grade_tukin',
                'jumlah_tukin_kotor',
                'jumlah_tukin_bersih',
                'gapok',
                'jumlah_uang_makan_harian',
                'foto_profil_url',
                'instansi',
            ]);
        });
    }
};
