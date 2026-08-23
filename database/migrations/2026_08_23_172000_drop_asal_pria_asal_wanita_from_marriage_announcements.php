<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marriage_announcements', function (Blueprint $table) {
            $table->dropColumn(['asal_pria', 'asal_wanita']);
        });
    }

    public function down(): void
    {
        Schema::table('marriage_announcements', function (Blueprint $table) {
            $table->string('asal_pria', 255)->nullable()->after('bin_pria');
            $table->string('asal_wanita', 255)->nullable()->after('binti_wanita');
        });
    }
};
