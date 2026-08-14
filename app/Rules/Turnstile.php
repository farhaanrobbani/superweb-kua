<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Turnstile implements ValidationRule
{
    /**
     * Validate the Cloudflare Turnstile token.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');

        if (! $secret) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Verifikasi Turnstile tidak valid. Silakan muat ulang halaman dan coba lagi.');

            return;
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $success = $response->successful() && $response->json('success') === true;
        } catch (\Exception $e) {
            $success = false;
        }

        if (! $success) {
            $fail('Verifikasi Turnstile gagal. Silakan coba lagi.');
        }
    }
}
