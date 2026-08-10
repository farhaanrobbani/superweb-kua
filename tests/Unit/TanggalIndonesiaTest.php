<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TanggalIndonesiaTest extends TestCase
{
    public function test_numeric_date_with_slashes_keeps_month(): void
    {
        $this->assertSame('08/08/2026', tanggal_indonesia('2026-08-08', 'd/m/Y'));
    }

    public function test_day_and_month_without_leading_zero(): void
    {
        $this->assertSame('8/8/2026', tanggal_indonesia('2026-08-08', 'j/n/Y'));
    }

    public function test_full_date_in_indonesian_locale(): void
    {
        $this->assertSame('08 Agustus 2026', tanggal_indonesia('2026-08-08'));
    }

    public function test_accepts_carbon_instance(): void
    {
        $this->assertSame('08/08/2026', tanggal_indonesia(\Carbon\Carbon::parse('2026-08-08'), 'd/m/Y'));
    }
}
