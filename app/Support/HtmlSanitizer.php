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
        'th' => ['colspan', 'rowspan', 'align'],
        'td' => ['colspan', 'rowspan', 'align'],
        'ol' => ['start'],
    ];

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
    }

    private static function cleanUrl(DOMElement $node, string $attr): void
    {
        $url = trim((string) $node->getAttribute($attr));
        if ($url === '') {
            return;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
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
