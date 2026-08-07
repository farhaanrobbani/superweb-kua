<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navbar_items', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('key');
            $table->foreign('parent_id')->references('id')->on('navbar_items')->onDelete('cascade');
        });

        $tentangId = DB::table('navbar_items')->where('key', 'tentang')->value('id');

        if ($tentangId) {
            DB::table('navbar_items')
                ->where('group', 'tentang')
                ->update(['parent_id' => $tentangId]);
        }

        if (Schema::hasTable('services')) {
            $layananId = DB::table('navbar_items')->where('key', 'layanan')->value('id');

            if ($layananId) {
                DB::table('navbar_items')->insertUsing(
                    ['key', 'label', 'description', 'url', 'embed_url', 'icon', 'parent_id', 'sort_order', 'active', 'has_submenu', 'created_at', 'updated_at'],
                    DB::table('services')->selectRaw(
                        "CONCAT('layanan-', id) as `key`, name as label, description, url, embed_url, icon, {$layananId} as parent_id, sort_order, active, 0 as has_submenu, NOW() as created_at, NOW() as updated_at"
                    )
                );
            }
        }

        Schema::table('navbar_items', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }

    public function down(): void
    {
        Schema::table('navbar_items', function (Blueprint $table) {
            $table->string('group', 20)->default('main')->after('icon');
        });

        $tentangId = DB::table('navbar_items')->where('key', 'tentang')->value('id');

        if ($tentangId) {
            DB::table('navbar_items')
                ->where('parent_id', $tentangId)
                ->update(['group' => 'tentang']);
        }

        Schema::table('navbar_items', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
