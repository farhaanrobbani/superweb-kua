<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('announcements', 'video_url')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('video_url');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('announcements', 'video_url')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->text('video_url')->nullable()->after('image');
            });
        }
    }
};
