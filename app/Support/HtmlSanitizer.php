<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const TAGS = [
        'p', 'br', 'strong', 'em', 'u', 's', 'sub', 'sup',
        'h2', 'h3', 'h4',
        'ul', 'ol', 'li',
        'blockquote', 'hr',
        'a', 'img',
        'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'span',
    ];

    private const ATTRS = [
        'a' => ['href', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'th' => ['colspan', 'rowspan', 'align', 'style'],
        'td' => ['colspan', 'rowspan', 'align', 'style'],
        'tr' => ['style'],
        'table' => ['style'],
        'ol' => ['start'],
        'p' => ['style'],
        'span' => ['style'],
        'h2' => ['style'],
        'h3' => ['style'],
        'h4' => ['style'],
        'li' => ['style'],
        'blockquote' => ['style'],
        'figcaption' => ['style'],
    ];

    private const SAFE_STYLE_PROPS = [
        'font-size', 'font-family', 'font-weight', 'font-style', 'text-decoration',
        'line-height', 'color', 'background-color', 'text-align', 'vertical-align',
        'width', 'height', 'max-width', 'max-height', 'float',
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border', 'border-width', 'border-style', 'border-color', 'border-collapse',
        'letter-spacing', 'text-indent', 'white-space',
    ];

    public static function toHtml(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        if (preg_match('/<[a-z!][a-z0-9]*[^>]*>/i', $text) === 1) {
            return $text;
        }

        $escaped = htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
        $blocks = preg_split('/\n\s*\n+/', $escaped);

        return implode('', array_map(
            fn (string $block) => '<p>' . nl2br($block) . '</p>',
            $blocks
        ));
    }

    public static function normalize(?string $text): string
    {
        return self::sanitize(self::toHtml($text));
    }

    public static function sanitize(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML(
            '<?xml encoding="UTF-8"><body>'.$html.'</body>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $body = $doc->getElementsByTagName('body')->item(0);
        if (! $body) {
            libxml_clear_errors();

            return htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
        }

        self::cleanNodes($body, $doc);
        libxml_clear_errors();

        $out = '';
        foreach ($body->childNodes as $node) {
            $out .= $doc->saveHTML($node);
        }

        return $out;
    }

    private static function cleanNodes(DOMNode $parent, DOMDocument $doc): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof DOMElement) {
                if (! in_array($node->tagName, self::TAGS, true)) {
                    self::unwrap($node);

                    continue;
                }

                self::cleanAttributes($node);

                if ($node->tagName === 'a') {
                    self::cleanUrl($node, 'href');
                }
                if ($node->tagName === 'img') {
                    self::cleanUrl($node, 'src');
                }

                if ($node->tagName === 'img' && empty($node->getAttribute('src'))) {
                    $node->parentNode?->removeChild($node);

                    continue;
                }
                if ($node->tagName === 'a' && empty($node->getAttribute('href'))) {
                    $node->removeAttribute('href');
                }
            }

            if ($node->hasChildNodes()) {
                self::cleanNodes($node, $doc);
            }
        }
    }

    private static function cleanAttributes(DOMElement $node): void
    {
        $allowed = self::ATTRS[$node->tagName] ?? [];
        foreach (iterator_to_array($node->attributes) as $attr) {
            if (! in_array($attr->name, $allowed, true) || str_starts_with($attr->name, 'on')) {
                $node->removeAttribute($attr->name);
            }
        }

        if (in_array('style', $allowed, true)) {
            self::cleanStyle($node);
        }
    }

    private static function cleanStyle(DOMElement $node): void
    {
        $style = trim($node->getAttribute('style'));
        if ($style === '') {
            $node->removeAttribute('style');

            return;
        }

        $cleaned = [];
        foreach (explode(';', $style) as $declaration) {
            $parts = explode(':', $declaration, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $property = strtolower(trim($parts[0]));
            $value = trim($parts[1]);

            if (! preg_match('/^[a-z-]+$/', $property) || ! in_array($property, self::SAFE_STYLE_PROPS, true)) {
                continue;
            }

            if (self::isUnsafeStyleValue($value)) {
                continue;
            }

            $cleaned[] = $property.': '.$value;
        }

        if ($cleaned === []) {
            $node->removeAttribute('style');
        } else {
            $node->setAttribute('style', implode('; ', $cleaned));
        }
    }

    private static function isUnsafeStyleValue(string $value): bool
    {
        $decoded = preg_replace_callback(
            '/\\\\([0-9a-fA-F]{1,6}\s?|.)/',
            fn (array $m): string => self::decodeCssEscape($m[1]),
            $value
        );

        return preg_match('/(url\s*\(|expression|javascript:|vbscript:|@import|behavior\s*:|-moz-binding)/i', $decoded) === 1;
    }

    private static function decodeCssEscape(string $escape): string
    {
        if (preg_match('/^[0-9a-fA-F]{1,6}\s?$/', $escape) === 1) {
            $codePoint = hexdec($escape);

            return $codePoint > 0 && $codePoint <= 0x10FFFF ? mb_chr($codePoint, 'UTF-8') : '';
        }

        return $escape === '' ? '' : $escape[0];
    }

    private static function cleanUrl(DOMElement $node, string $attr): void
    {
        $url = trim((string) $node->getAttribute($attr));
        if ($url === '') {
            return;
        }

        $stripped = preg_replace('/[\x00-\x20\x7F]/', '', $url);

        $scheme = strtolower((string) parse_url($stripped, PHP_URL_SCHEME));
        if ($scheme !== '' && ! in_array($scheme, ['http', 'https', 'mailto'], true)) {
            $node->removeAttribute($attr);

            return;
        }

        if (preg_match('/\s+on[a-z]+\s*=/i', $url) === 1) {
            $node->removeAttribute($attr);
        }
    }

    private static function unwrap(DOMElement $node): void
    {
        $children = iterator_to_array($node->childNodes);
        foreach ($children as $child) {
            $node->parentNode?->insertBefore($child, $node);
        }
        $node->parentNode?->removeChild($node);
    }
}
