<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_type_id')->constrained()->cascadeOnDelete();
            $table->string('nama_pemohon', 150);
            $table->string('kontak', 100)->nullable();
            $table->json('data')->nullable();
            $table->string('status', 20)->default('baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
