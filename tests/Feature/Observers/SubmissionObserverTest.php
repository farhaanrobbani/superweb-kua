<?php

namespace Tests\Feature\Observers;

use App\Jobs\SendSubmissionTelegramNotification;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubmissionObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_dispatches_notification_job_on_created(): void
    {
        Queue::fake();

        Submission::factory()->create();

        Queue::assertPushed(SendSubmissionTelegramNotification::class);
    }
}
