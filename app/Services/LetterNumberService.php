<?php

namespace App\Services;

use App\Models\Letter;

class LetterNumberService
{
    private const ROMAN = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];

    public function next(Letter $letter): string
    {
        $year = $letter->tanggal_surat?->year ?? now()->year;

        $counter = Letter::where('letter_type_id', $letter->letter_type_id)
            ->where('status', Letter::STATUS_TERBIT)
            ->whereYear('tanggal_surat', $year)
            ->count() + 1;

        $month = $letter->tanggal_surat?->month ?? now()->month;
        $code = $letter->letterType->code;

        return sprintf('%s.%03d/KUA.%s/%d', $code, $counter, self::ROMAN[$month], $year);
    }
}
