<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class ColonTableFormatter
{
    private const MAX_LABEL_LENGTH = 40;

    private const MIN_LABEL_LENGTH = 2;

    public static function format(?string $html): string
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

            return $html;
        }

        $items = [];
        $pendingRows = [];
        $pendingIndent = null;

        foreach (iterator_to_array($body->childNodes) as $node) {
            if ($node instanceof DOMText && trim($node->nodeValue ?? '') === '') {
                continue;
            }

            if ($node instanceof DOMElement && $node->tagName === 'p') {
                $lines = self::paragraphLines($node);
                if ($lines !== null) {
                    $pendingRows = array_merge($pendingRows, $lines);
                    $pendingIndent ??= self::leftIndent($node);

                    continue;
                }
            }

            $items = array_merge($items, self::flushPending($pendingRows, $pendingIndent, $doc));
            $pendingRows = [];
            $pendingIndent = null;
            $items[] = $node;
        }

        $items = array_merge($items, self::flushPending($pendingRows, $pendingIndent, $doc));

        foreach (iterator_to_array($body->childNodes) as $node) {
            $body->removeChild($node);
        }
        foreach ($items as $item) {
            $body->appendChild($item);
        }
        libxml_clear_errors();

        $out = '';
        foreach ($body->childNodes as $node) {
            $out .= $doc->saveHTML($node);
        }

        return $out;
    }

    /**
     * Split a single-line paragraph into aligned "Label : isi" rows.
     *
     * @return array<int, array{0: string, 1: string}>|null null when the paragraph is not a colon-list
     */
    private static function paragraphLines(DOMElement $p): ?array
    {
        $inner = '';
        foreach ($p->childNodes as $child) {
            $inner .= $p->ownerDocument?->saveHTML($child);
        }

        $rows = [];
        foreach (preg_split('/<br\s*\/?>/i', $inner) as $rawLine) {
            if (self::cleanText($rawLine) === '') {
                continue;
            }

            $parts = self::splitLabelValue($rawLine);
            if ($parts === null) {
                return null;
            }

            $rows[] = $parts;
        }

        return $rows === [] ? null : $rows;
    }

    /**
     * Split a line into label and value when it matches "Label : isi".
     *
     * @return array{0: string, 1: string}|null
     */
    private static function splitLabelValue(string $line): ?array
    {
        $inTag = false;
        $length = strlen($line);

        for ($i = 0; $i < $length; $i++) {
            $char = $line[$i];
            if ($inTag) {
                if ($char === '>') {
                    $inTag = false;
                }

                continue;
            }

            if ($char === '<') {
                $inTag = true;

                continue;
            }

            if ($char !== ':') {
                continue;
            }

            $labelText = self::cleanText(substr($line, 0, $i));
            $valueHtml = self::cleanText(substr($line, $i + 1));

            if (! self::isValidLabel($labelText) || $valueHtml === '') {
                return null;
            }

            return [
                htmlspecialchars($labelText, ENT_QUOTES, 'UTF-8'),
                substr($line, $i + 1),
            ];
        }

        return null;
    }

    private static function isValidLabel(string $label): bool
    {
        $length = mb_strlen($label, 'UTF-8');
        if ($length < self::MIN_LABEL_LENGTH || $length > self::MAX_LABEL_LENGTH) {
            return false;
        }

        return preg_match('/[.!?]\s*$/u', $label) !== 1;
    }

    private static function cleanText(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace(
            '/^[\s\x{00A0}\x{2007}\x{202F}]+|[\s\x{00A0}\x{2007}\x{202F}]+$/u',
            '',
            $text
        ) ?? '');
    }

    private static function leftIndent(DOMElement $p): int
    {
        $style = $p->getAttribute('style');
        if (preg_match('/(?:padding|margin)-left\s*:\s*(\d+(?:\.\d+)?)\s*px/i', $style, $m)) {
            return (int) round((float) $m[1]);
        }

        return 0;
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $rows
     * @return array<int, DOMNode>
     */
    private static function flushPending(array $rows, ?int $indent, DOMDocument $doc): array
    {
        if ($rows === []) {
            return [];
        }

        $width = self::labelWidth($rows);
        $html = '<table style="border-collapse:collapse;margin-bottom:12px;'
            .($indent ? 'margin-left:'.$indent.'px;' : '')
            .'">';

        foreach ($rows as [$label, $value]) {
            $html .= '<tr>'
                .'<td style="width:'.$width.'px;text-align:left;vertical-align:top">'.$label.'</td>'
                .'<td style="width:14px;text-align:left;vertical-align:top">:</td>'
                .'<td style="text-align:left;vertical-align:top">'.$value.'</td>'
                .'</tr>';
        }
        $html .= '</table>';

        libxml_use_internal_errors(true);
        $tmp = new DOMDocument('1.0', 'UTF-8');
        $tmp->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $node = $tmp->documentElement ? $doc->importNode($tmp->documentElement, true) : null;
        libxml_clear_errors();

        return $node ? [$node] : [];
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $rows
     */
    private static function labelWidth(array $rows): int
    {
        $max = 0;
        foreach ($rows as [$label]) {
            $max = max($max, mb_strlen($label, 'UTF-8'));
        }

        return max(100, (int) ceil($max * 6.5));
    }
}
