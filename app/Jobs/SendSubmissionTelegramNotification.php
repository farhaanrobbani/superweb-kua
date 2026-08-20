<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Support\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSubmissionTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public Submission $submission,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $this->submission->load('letterType');

        $lines = [];
        $lines[] = '📋 <b>Permohonan Baru</b>';
        $lines[] = '';
        $lines[] = 'Jenis Surat: ' . $this->submission->letterType->name;
        $lines[] = 'Pemohon: ' . e($this->submission->nama_pemohon);
        $lines[] = 'Kontak: ' . e((string) $this->submission->kontak);
        $lines[] = 'Status: Baru';
        $lines[] = 'Tanggal: ' . $this->submission->created_at->format('d/m/Y H:i');
        $lines[] = '';

        foreach ($this->submission->permohonanFields() as $field) {
            $value = $this->submission->data[$field['name']] ?? '—';
            $lines[] = e($field['label'] ?? $field['name']) . ': ' . e((string) $value);
        }

        $lines[] = '';
        if ($this->submission->token) {
            $lines[] = '<a href="' . route('permohonan.track', $this->submission->token) . '">Lihat Status Permohonan</a>';
        }

        $plainText = strip_tags(implode("\n", $lines));
        $waUrl = TelegramService::buildWhatsAppUrl($this->submission->kontak, $plainText);
        if ($waUrl) {
            $lines[] = '<a href="' . $waUrl . '">📲 Kirim ke WhatsApp</a>';
        }

        $telegram->send(implode("\n", $lines));
    }
}
