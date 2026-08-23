<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marriage_announcements', function (Blueprint $table) {
            $table->string('no_pendaftaran', 80)->nullable()->after('id');
            $table->string('bin_pria', 120)->nullable()->after('nama_pria');
            $table->string('alamat_pria', 255)->nullable()->after('asal_pria');
            $table->string('binti_wanita', 120)->nullable()->after('nama_wanita');
            $table->string('alamat_wanita', 255)->nullable()->after('asal_wanita');
            $table->string('status_wali', 150)->nullable()->after('tempat_nikah');
        });
    }

    public function down(): void
    {
        Schema::table('marriage_announcements', function (Blueprint $table) {
            $table->dropColumn(['no_pendaftaran', 'bin_pria', 'alamat_pria', 'binti_wanita', 'alamat_wanita', 'status_wali']);
        });
    }
};
