<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_types', function (Blueprint $table) {
            $table->text('kop_footer')->nullable()->after('publik');
            $table->boolean('kop_footer_enabled')->default(false)->after('kop_footer');
        });
    }

    public function down(): void
    {
        Schema::table('letter_types', function (Blueprint $table) {
            $table->dropColumn(['kop_footer_enabled', 'kop_footer']);
        });
    }
};
