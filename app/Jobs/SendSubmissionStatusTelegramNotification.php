<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Support\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSubmissionStatusTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public Submission $submission,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $this->submission->load('letterType');

        $statuses = Submission::statuses();
        $oldLabel = $statuses[$this->oldStatus] ?? $this->oldStatus;
        $newLabel = $statuses[$this->newStatus] ?? $this->newStatus;

        $emoji = match ($this->newStatus) {
            Submission::STATUS_DIPROSES => '🔄',
            Submission::STATUS_SELESAI  => '✅',
            Submission::STATUS_DITOLAK  => '❌',
            default                     => '🔔',
        };

        $lines = [];
        $lines[] = "{$emoji} <b>Status Permohonan Diperbarui</b>";
        $lines[] = '';
        $lines[] = 'Jenis Surat: ' . $this->submission->letterType->name;
        $lines[] = 'Pemohon: ' . e($this->submission->nama_pemohon);
        $lines[] = "Status: {$oldLabel} → {$newLabel}";

        if ($this->submission->catatan) {
            $lines[] = 'Catatan: ' . e($this->submission->catatan);
        }

        $lines[] = 'Tanggal: ' . now()->format('d/m/Y H:i');
        $lines[] = '';
        if ($this->submission->token) {
            $lines[] = 'Kode Tracking: <code>' . $this->submission->token . '</code>';
            $lines[] = '<a href="' . route('permohonan.track', $this->submission->token) . '">Lihat Status Permohonan</a>';
        }

        $waLines = [];
        $waLines[] = "{$emoji} Status Permohonan Diperbarui";
        $waLines[] = '';
        $waLines[] = 'Jenis Surat: ' . $this->submission->letterType->name;
        $waLines[] = 'Pemohon: ' . e($this->submission->nama_pemohon);
        $waLines[] = "Status: {$oldLabel} → {$newLabel}";
        if ($this->submission->catatan) {
            $waLines[] = 'Catatan: ' . e($this->submission->catatan);
        }
        $waLines[] = 'Tanggal: ' . now()->format('d/m/Y H:i');
        if ($this->submission->token) {
            $waLines[] = '';
            $waLines[] = 'Kode Tracking: ' . $this->submission->token;
            $waLines[] = '';
            $waLines[] = 'Tracking: ' . route('permohonan.track', $this->submission->token);
        }

        $waUrl = TelegramService::buildWhatsAppUrl($this->submission->kontak, implode("\n", $waLines));
        if ($waUrl) {
            $lines[] = '<a href="' . $waUrl . '">📲 Kirim ke WhatsApp</a>';
        }

        $telegram->send(implode("\n", $lines));
    }
}
