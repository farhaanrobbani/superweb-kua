<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendSubmissionTelegramNotification;
use App\Models\KuaSetting;
use App\Models\Submission;
use App\Support\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SendSubmissionTelegramNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        KuaSetting::set('telegram_bot_token', 'test-token');
        KuaSetting::set('telegram_chat_id', '-1001234567890');
    }

    public function test_job_sends_telegram_message_with_submission_data(): void
    {
        $submission = Submission::factory()->create([
            'nama_pemohon' => 'Ahmad Fauzi',
            'kontak' => '081234567890',
        ]);

        $job = new SendSubmissionTelegramNotification($submission);

        Http::fake();
        $job->handle(new TelegramService);

        Http::assertSent(function ($request) {
            $text = $request->data()['text'] ?? '';

            return str_contains($request->url(), 'sendMessage')
                && str_contains($text, 'Permohonan Baru')
                && str_contains($text, 'Ahmad Fauzi')
                && $request->data()['parse_mode'] === 'HTML';
        });
    }

    public function test_observer_dispatches_job_on_submission_created(): void
    {
        Queue::fake();

        Submission::factory()->create();

        Queue::assertPushed(SendSubmissionTelegramNotification::class);
    }
}
