<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('religious_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('alur')->nullable();
            $table->text('sop')->nullable();
            $table->string('persyaratan_label', 50)->nullable();
            $table->string('alur_label', 50)->nullable();
            $table->string('sop_label', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religious_services');
    }
};
