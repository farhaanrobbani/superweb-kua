<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services')) {
            Schema::dropIfExists('services');
        }
    }

    public function down(): void
    {
        //
    }
};
