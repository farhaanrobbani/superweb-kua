<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfSupport
{
    public static function registerArialFonts(): void
    {
        $fonts = [
            ['weight' => 'normal', 'style' => 'normal', 'file' => 'LiberationSans-Regular.ttf'],
            ['weight' => 'bold', 'style' => 'normal', 'file' => 'LiberationSans-Bold.ttf'],
            ['weight' => 'normal', 'style' => 'italic', 'file' => 'LiberationSans-Italic.ttf'],
            ['weight' => 'bold', 'style' => 'italic', 'file' => 'LiberationSans-BoldItalic.ttf'],
        ];

        $metrics = Pdf::getDomPDF()->getFontMetrics();
        foreach ($fonts as $font) {
            $path = storage_path('fonts/' . $font['file']);
            if (! file_exists($path)) {
                continue;
            }
            $metrics->registerFont([
                'family' => 'Arial',
                'weight' => $font['weight'],
                'style' => $font['style'],
            ], $path);
        }
    }
}
