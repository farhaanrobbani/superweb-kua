<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('slug', 220)->nullable()->after('content');
            $table->enum('category', ['news', 'article', 'announcement'])->default('announcement')->after('slug');
            $table->text('excerpt')->nullable()->after('category');
            $table->foreignId('author_id')->nullable()->after('excerpt')->constrained('users')->nullOnDelete();
        });

        $used = [];
        foreach (DB::table('announcements')->orderBy('id')->get() as $row) {
            $base = Str::slug($row->title);
            $slug = $base === '' ? 'pengumuman-' . $row->id : $base;
            $candidate = $slug;
            $i = 2;
            while (isset($used[$candidate])) {
                $candidate = $slug . '-' . $i++;
            }
            $used[$candidate] = true;

            DB::table('announcements')->where('id', $row->id)->update([
                'slug' => $candidate,
                'excerpt' => Str::limit(strip_tags($row->content), 160),
            ]);
        }

        Schema::table('announcements', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropUnique('announcements_slug_unique');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('author_id');
            $table->dropColumn(['slug', 'category', 'excerpt']);
        });
    }
};
