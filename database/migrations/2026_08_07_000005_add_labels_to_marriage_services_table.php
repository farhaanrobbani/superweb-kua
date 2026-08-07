<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marriage_services', function (Blueprint $table) {
            $table->string('persyaratan_label', 50)->nullable()->after('sop');
            $table->string('alur_label', 50)->nullable()->after('persyaratan_label');
            $table->string('sop_label', 50)->nullable()->after('alur_label');
        });
    }

    public function down(): void
    {
        Schema::table('marriage_services', function (Blueprint $table) {
            $table->dropColumn(['persyaratan_label', 'alur_label', 'sop_label']);
        });
    }
};
