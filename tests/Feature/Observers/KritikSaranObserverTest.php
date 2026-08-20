<?php

namespace Tests\Feature\Observers;

use App\Jobs\SendKritikSaranTelegramNotification;
use App\Models\KritikSaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class KritikSaranObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_dispatches_notification_job_on_created(): void
    {
        Queue::fake();

        KritikSaran::factory()->create();

        Queue::assertPushed(SendKritikSaranTelegramNotification::class);
    }
}
