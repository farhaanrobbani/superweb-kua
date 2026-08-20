<?php

namespace App\Jobs;

use App\Models\KritikSaran;
use App\Support\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendKritikSaranTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public KritikSaran $kritikSaran,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $lines = [];
        $lines[] = '💬 <b>Kritik &amp; Saran Baru</b>';
        $lines[] = '';
        $lines[] = 'Nama: ' . e($this->kritikSaran->nama);
        $lines[] = 'Kontak: ' . e((string) $this->kritikSaran->kontak);
        $lines[] = 'Kategori: ' . e((string) $this->kritikSaran->kategori);
        $lines[] = 'Tanggal: ' . $this->kritikSaran->created_at->format('d/m/Y H:i');
        $lines[] = '';
        $lines[] = '<b>Isi:</b>';
        $lines[] = e($this->kritikSaran->isi);

        $telegram->send(implode("\n", $lines));
    }
}
