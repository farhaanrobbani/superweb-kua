<?php

namespace App\Support;

use App\Models\KuaSetting;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;

class SubmissionPdf
{
    public static function build(Submission $submission): array
    {
        $submission->loadMissing('letterType');

        PdfSupport::registerArialFonts();

        $kabupaten = KuaSetting::get('kabupaten') ?? '';

        $pdf = Pdf::loadView('pdf.permohonan', [
            'submission' => $submission,
            'kabupaten' => $kabupaten,
            'body' => self::bodyHtml($submission),
        ])->setPaper('a4');

        return [$pdf, 'surat-permohonan-' . $submission->id . '.pdf'];
    }

    public static function bodyHtml(Submission $submission): string
    {
        return PdfSupport::resolveLocalImages(
            ColonTableFormatter::format($submission->renderPermohonanBody(), 190)
        );
    }
}
