<?php

namespace App\Observers;

use App\Jobs\SendSubmissionStatusTelegramNotification;
use App\Jobs\SendSubmissionTelegramNotification;
use App\Models\Submission;

class SubmissionObserver
{
    public function created(Submission $submission): void
    {
        SendSubmissionTelegramNotification::dispatch($submission);
    }

    public function updated(Submission $submission): void
    {
        $oldStatus = $submission->getOriginal('status');
        $newStatus = $submission->status;

        if ($oldStatus === $newStatus) {
            return;
        }

        SendSubmissionStatusTelegramNotification::dispatch(
            $submission,
            $oldStatus,
            $newStatus,
        );
    }
}
