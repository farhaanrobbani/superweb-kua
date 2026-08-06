<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfSupport
{
    public static function resolveLocalImages(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return preg_replace_callback('/src=(["\'])([^"\']+)\1/i', function (array $match) {
            $url = html_entity_decode($match[2], ENT_QUOTES, 'UTF-8');
            $path = (string) parse_url($url, PHP_URL_PATH);

            if (str_starts_with($path, '/storage/')) {
                $relative = substr($path, strlen('/storage/'));
                $local = Storage::disk('public')->path($relative);

                return 'src=' . $match[1] . $local . $match[1];
            }

            return $match[0];
        }, $html) ?? $html;
    }

    public static function parseKopTeks(?string $teks): array
    {
        $lines = [];
        if (! $teks) {
            return $lines;
        }

        foreach (preg_split('/\r\n|\r|\n/', $teks) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, '###')) {
                $text = trim(substr($line, 3));
                $class = 'sub2';
            } elseif (str_starts_with($line, '##')) {
                $text = trim(substr($line, 2));
                $class = 'sub';
            } elseif (str_starts_with($line, '#')) {
                $text = trim(substr($line, 1));
                $class = 'judul';
            } else {
                $text = $line;
                $class = 'baris';
            }

            if ($text !== '') {
                $lines[] = ['text' => $text, 'class' => $class];
            }
        }

        return $lines;
    }

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
