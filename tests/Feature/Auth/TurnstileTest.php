<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TurnstileTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_succeeds_without_turnstile_when_not_configured(): void
    {
        config(['services.turnstile.secret_key' => null, 'services.turnstile.site_key' => null]);

        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_login_rejects_missing_turnstile_token_when_configured(): void
    {
        config(['services.turnstile.secret_key' => 'secret', 'services.turnstile.site_key' => 'site']);

        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('cf-turnstile-response');

        $this->assertGuest();
    }

    public function test_login_rejects_invalid_turnstile_token_when_configured(): void
    {
        Http::fake([
            'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => false]),
        ]);

        config(['services.turnstile.secret_key' => 'secret', 'services.turnstile.site_key' => 'site']);

        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'cf-turnstile-response' => 'token-salah',
        ])->assertSessionHasErrors('cf-turnstile-response');

        $this->assertGuest();
    }

    public function test_login_succeeds_with_valid_turnstile_token_when_configured(): void
    {
        Http::fake([
            'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
        ]);

        config(['services.turnstile.secret_key' => 'secret', 'services.turnstile.site_key' => 'site']);

        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'cf-turnstile-response' => 'token-benar',
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }
}
