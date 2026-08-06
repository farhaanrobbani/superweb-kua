<?php

namespace Tests\Unit;

use App\Support\PdfSupport;
use PHPUnit\Framework\TestCase;

class KopTeksTest extends TestCase
{
    public function test_parses_markers_into_classes(): void
    {
        $teks = "#KUA KECAMATAN CONTOH\n##KECAMATAN CONTOH KABUPATEN CONTOH\nJl. Contoh No. 1";

        $this->assertSame([
            ['text' => 'KUA KECAMATAN CONTOH', 'class' => 'judul'],
            ['text' => 'KECAMATAN CONTOH KABUPATEN CONTOH', 'class' => 'sub'],
            ['text' => 'Jl. Contoh No. 1', 'class' => 'baris'],
        ], PdfSupport::parseKopTeks($teks));
    }

    public function test_returns_empty_array_for_blank(): void
    {
        $this->assertSame([], PdfSupport::parseKopTeks(''));
        $this->assertSame([], PdfSupport::parseKopTeks(null));
    }

    public function test_skips_empty_lines(): void
    {
        $teks = "#Judul\n\n##Sub\n";

        $this->assertSame([
            ['text' => 'Judul', 'class' => 'judul'],
            ['text' => 'Sub', 'class' => 'sub'],
        ], PdfSupport::parseKopTeks($teks));
    }

    public function test_handles_crlf_and_trims_marker_whitespace(): void
    {
        $teks = "# Judul\r\n##  Sub \r\nBaris biasa";

        $this->assertSame([
            ['text' => 'Judul', 'class' => 'judul'],
            ['text' => 'Sub', 'class' => 'sub'],
            ['text' => 'Baris biasa', 'class' => 'baris'],
        ], PdfSupport::parseKopTeks($teks));
    }
}
