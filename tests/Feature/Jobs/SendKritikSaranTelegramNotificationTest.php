<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendKritikSaranTelegramNotification;
use App\Models\KritikSaran;
use App\Support\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SendKritikSaranTelegramNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.telegram.bot_token' => 'test-token']);
        config(['services.telegram.chat_id' => '-1001234567890']);
    }

    public function test_job_sends_telegram_message_with_kritik_saran_data(): void
    {
        $kritikSaran = KritikSaran::factory()->create([
            'nama' => 'Siti Rahma',
            'kategori' => 'Pelayanan',
            'isi' => 'Pelayanan sudah cukup baik.',
        ]);

        $job = new SendKritikSaranTelegramNotification($kritikSaran);

        Http::fake();
        $job->handle(new TelegramService);

        Http::assertSent(function ($request) {
            $text = $request->data()['text'] ?? '';

            return str_contains($request->url(), 'sendMessage')
                && str_contains($text, 'Siti Rahma')
                && $request->data()['parse_mode'] === 'HTML';
        });
    }

    public function test_observer_dispatches_job_on_kritik_saran_created(): void
    {
        Queue::fake();

        KritikSaran::factory()->create();

        Queue::assertPushed(SendKritikSaranTelegramNotification::class);
    }
}
