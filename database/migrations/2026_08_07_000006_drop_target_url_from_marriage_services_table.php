<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marriage_services', function (Blueprint $table) {
            $table->dropColumn('target_url');
        });
    }

    public function down(): void
    {
        Schema::table('marriage_services', function (Blueprint $table) {
            $table->string('target_url', 255)->nullable()->after('sop_label');
        });
    }
};