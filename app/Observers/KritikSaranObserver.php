<?php

namespace App\Observers;

use App\Jobs\SendKritikSaranTelegramNotification;
use App\Models\KritikSaran;

class KritikSaranObserver
{
    public function created(KritikSaran $kritikSaran): void
    {
        SendKritikSaranTelegramNotification::dispatch($kritikSaran);
    }
}
