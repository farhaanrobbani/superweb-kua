<?php

namespace App\Support;

class VideoEmbed
{
    public static function url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be'], true)) {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $host === 'youtu.be'
                ? trim($parts['path'] ?? '', '/')
                : ($query['v'] ?? trim($parts['path'] ?? '', '/'));

            return preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId) ? 'https://www.youtube.com/embed/'.$videoId : null;
        }

        if (str_ends_with($host, 'kemenag.go.id')) {
            return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
        }

        return null;
    }
}
