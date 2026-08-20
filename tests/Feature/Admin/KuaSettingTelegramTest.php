<?php

namespace Tests\Feature\Admin;

use App\Models\KuaSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KuaSettingTelegramTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_KEPALA]);
    }

    public function test_guest_cannot_access_test_telegram(): void
    {
        $this->postJson(route('kua-settings.test-telegram'))
            ->assertUnauthorized();
    }

    public function test_staff_cannot_access_test_telegram(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

        $this->actingAs($staff)
            ->postJson(route('kua-settings.test-telegram'))
            ->assertForbidden();
    }

    public function test_kepala_returns_error_when_config_empty(): void
    {
        $this->actingAs($this->user)
            ->postJson(route('kua-settings.test-telegram'))
            ->assertOk()
            ->assertJsonPath('ok', false)
            ->assertJsonPath('message', 'Bot Token dan Chat ID harus diisi.');
    }

    public function test_kepala_sends_test_message_successfully(): void
    {
        Http::fake();

        KuaSetting::set('telegram_bot_token', 'test-token');
        KuaSetting::set('telegram_chat_id', '-1001234567890');

        $this->actingAs($this->user)
            ->postJson(route('kua-settings.test-telegram'))
            ->assertOk()
            ->assertJsonPath('ok', true);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'test-token/sendMessage')
                && str_contains($request->data()['text'], 'Test Koneksi Berhasil');
        });
    }

    public function test_kepala_uses_form_values_over_saved(): void
    {
        Http::fake();

        $this->actingAs($this->user)
            ->postJson(route('kua-settings.test-telegram'), [
                'telegram_bot_token' => 'form-token',
                'telegram_chat_id' => '-100999',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'form-token/sendMessage')
                && $request->data()['chat_id'] === '-100999';
        });
    }

    public function test_kepala_handles_telegram_api_error(): void
    {
        Http::fake([
            'https://api.telegram.org/boterror-token/sendMessage' => Http::response([
                'ok' => false,
                'error_code' => 401,
                'description' => 'Unauthorized',
            ], 401),
        ]);

        $this->actingAs($this->user)
            ->postJson(route('kua-settings.test-telegram'), [
                'telegram_bot_token' => 'error-token',
                'telegram_chat_id' => '-100123',
            ])
            ->assertOk()
            ->assertJsonPath('ok', false)
            ->assertJsonFragment(['message' => 'Gagal: Unauthorized']);
    }
}
