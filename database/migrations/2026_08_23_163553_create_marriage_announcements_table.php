<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriage_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pria', 150);
            $table->string('asal_pria', 255)->nullable();
            $table->string('nama_wanita', 150);
            $table->string('asal_wanita', 255)->nullable();
            $table->date('tanggal_akad');
            $table->string('tempat_nikah', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_announcements');
    }
};
