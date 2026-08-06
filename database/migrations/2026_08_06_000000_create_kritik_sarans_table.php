<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kritik_sarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('kontak', 150)->nullable();
            $table->string('kategori', 50)->nullable();
            $table->text('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kritik_sarans');
    }
};
