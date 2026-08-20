<?php

namespace App\Observers;

use App\Jobs\SendSubmissionTelegramNotification;
use App\Models\Submission;

class SubmissionObserver
{
    public function created(Submission $submission): void
    {
        SendSubmissionTelegramNotification::dispatch($submission);
    }
}
