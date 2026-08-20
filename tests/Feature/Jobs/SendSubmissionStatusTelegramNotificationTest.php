<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendSubmissionStatusTelegramNotification;
use App\Models\KuaSetting;
use App\Models\Submission;
use App\Support\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SendSubmissionStatusTelegramNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        KuaSetting::set('telegram_bot_token', 'test-token');
        KuaSetting::set('telegram_chat_id', '-1001234567890');
    }

    public function test_job_sends_status_change_message(): void
    {
        $submission = Submission::factory()->create([
            'nama_pemohon' => 'Budi Santoso',
            'status' => Submission::STATUS_BARU,
        ]);

        $job = new SendSubmissionStatusTelegramNotification(
            $submission,
            Submission::STATUS_BARU,
            Submission::STATUS_DIPROSES,
        );

        Http::fake();
        $job->handle(new TelegramService);

        Http::assertSent(function ($request) {
            $text = $request->data()['text'] ?? '';

            return str_contains($request->url(), 'sendMessage')
                && str_contains($text, 'Status Permohonan Diperbarui')
                && str_contains($text, 'Budi Santoso')
                && str_contains($text, 'Baru → Diproses')
                && $request->data()['parse_mode'] === 'HTML';
        });
    }

    public function test_job_includes_catatan_when_present(): void
    {
        $submission = Submission::factory()->create([
            'status' => Submission::STATUS_BARU,
            'catatan' => 'Data tidak lengkap',
        ]);

        $job = new SendSubmissionStatusTelegramNotification(
            $submission,
            Submission::STATUS_BARU,
            Submission::STATUS_DITOLAK,
        );

        Http::fake();
        $job->handle(new TelegramService);

        Http::assertSent(function ($request) {
            $text = $request->data()['text'] ?? '';

            return str_contains($text, 'Catatan: Data tidak lengkap')
                && str_contains($text, 'Baru → Ditolak');
        });
    }

    public function test_job_omits_catatan_when_empty(): void
    {
        $submission = Submission::factory()->create([
            'status' => Submission::STATUS_BARU,
            'catatan' => null,
        ]);

        $job = new SendSubmissionStatusTelegramNotification(
            $submission,
            Submission::STATUS_BARU,
            Submission::STATUS_SELESAI,
        );

        Http::fake();
        $job->handle(new TelegramService);

        Http::assertSent(function ($request) {
            $text = $request->data()['text'] ?? '';

            return ! str_contains($text, 'Catatan:')
                && str_contains($text, 'Baru → Selesai');
        });
    }

    public function test_observer_dispatches_job_on_status_change(): void
    {
        Queue::fake();

        $submission = Submission::factory()->create([
            'status' => Submission::STATUS_BARU,
        ]);

        $submission->update(['status' => Submission::STATUS_DIPROSES]);

        Queue::assertPushed(SendSubmissionStatusTelegramNotification::class, function ($job) use ($submission) {
            return $job->submission->id === $submission->id
                && $job->oldStatus === Submission::STATUS_BARU
                && $job->newStatus === Submission::STATUS_DIPROSES;
        });
    }

    public function test_observer_does_not_dispatch_when_status_unchanged(): void
    {
        Queue::fake();

        $submission = Submission::factory()->create([
            'status' => Submission::STATUS_BARU,
        ]);

        $submission->update(['catatan' => 'Hanya update catatan']);

        Queue::assertNotPushed(SendSubmissionStatusTelegramNotification::class);
    }
}
