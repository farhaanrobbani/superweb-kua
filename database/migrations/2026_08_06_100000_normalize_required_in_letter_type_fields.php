<?php

use App\Models\LetterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('letter_types')->select('id', 'fields')->whereNotNull('fields')->get();

        foreach ($rows as $row) {
            $fields = json_decode((string) $row->fields, true);
            if (! is_array($fields)) {
                continue;
            }

            $normalized = LetterType::normalizeFieldOptions($fields);

            if ($normalized !== $fields) {
                DB::table('letter_types')
                    ->where('id', $row->id)
                    ->update(['fields' => json_encode($normalized)]);
            }
        }
    }

    public function down(): void
    {
        // Tidak ada operasi balik yang aman.
    }
};
