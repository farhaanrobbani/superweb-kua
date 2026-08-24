<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class KuaStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_kua_open_on_weekday_within_hours(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-24 10:00', 'Asia/Jakarta')); // Monday

        $this->assertTrue(is_kua_open());
        $this->assertSame('Buka • Tutup 16.00 WIB', kua_status_label());

        Carbon::setTestNow();
    }

    public function test_kua_closed_before_open(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-24 07:29', 'Asia/Jakarta')); // Monday before open

        $this->assertFalse(is_kua_open());
        $this->assertSame('Tutup • Buka 07.30 WIB', kua_status_label());

        Carbon::setTestNow();
    }

    public function test_kua_closed_after_close_on_friday(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-28 16:31', 'Asia/Jakarta')); // Friday after 16:30

        $this->assertFalse(is_kua_open());

        Carbon::setTestNow();
    }

    public function test_kua_closed_on_saturday(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-29 10:00', 'Asia/Jakarta')); // Saturday

        $this->assertFalse(is_kua_open());
        $this->assertSame('Tutup • Buka Senin 07.30 WIB', kua_status_label());

        Carbon::setTestNow();
    }

    public function test_welcome_shows_status_badge(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-24 10:00', 'Asia/Jakarta')); // Monday

        $this->get('/')
            ->assertOk()
            ->assertSee('Buka')
            ->assertSee('Senin–Kamis 07.30–16.00');

        Carbon::setTestNow();
    }
}
