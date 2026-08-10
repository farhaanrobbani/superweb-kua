<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_preserves_font_size_and_color_styles(): void
    {
        $html = '<p><span style="font-size: 18pt; color: red">Teks</span></p>';

        $this->assertSame(
            '<p><span style="font-size: 18pt; color: red">Teks</span></p>',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_preserves_text_align_on_blocks(): void
    {
        $html = '<p style="text-align: center">Judul tengah</p>';

        $this->assertSame(
            '<p style="text-align: center">Judul tengah</p>',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_preserves_table_cell_styles(): void
    {
        $html = '<table><tr><td style="text-align: right; vertical-align: top">Isi</td></tr></table>';

        $this->assertSame(
            '<table><tr><td style="text-align: right; vertical-align: top">Isi</td></tr></table>',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_keeps_formatting_tags(): void
    {
        $html = '<p><strong>Tebal</strong> dan <em>miring</em> serta <u>garis bawah</u>.</p>';

        $this->assertSame($html, HtmlSanitizer::sanitize($html));
    }

    public function test_strips_unsafe_css_functions_from_style(): void
    {
        $html = '<p style="background: url(javascript:alert(1)); color: red">Teks</p>';

        $this->assertSame(
            '<p style="color: red">Teks</p>',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_removes_style_when_nothing_safe_remains(): void
    {
        $html = '<p style="position: fixed; width: expression(alert(1))">Teks</p>';

        $this->assertSame('<p>Teks</p>', HtmlSanitizer::sanitize($html));
    }

    public function test_strips_style_from_elements_without_style_allowlist(): void
    {
        $html = '<strong style="color: red">Tebal</strong>';

        $this->assertSame('<strong>Tebal</strong>', HtmlSanitizer::sanitize($html));
    }

    public function test_strips_event_attributes_and_unsafe_schemes(): void
    {
        $html = '<a href="javascript:alert(1)" onclick="steal()">Klik</a>'
            .'<img src="http://example.com/a.jpg" onerror="alert(1)">';

        $this->assertSame(
            '<a>Klik</a><img src="http://example.com/a.jpg">',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_strips_obfuscated_javascript_scheme_with_control_chars(): void
    {
        $html = '<a href="java'."\t".'script:alert(1)">Klik</a>'
            .'<a href="java'."\n".'script:alert(1)">Klik2</a>'
            .'<a href="java'."\r".'script:alert(1)">Klik3</a>';

        $this->assertSame(
            '<a>Klik</a><a>Klik2</a><a>Klik3</a>',
            HtmlSanitizer::sanitize($html)
        );
    }

    public function test_allows_legit_schemes_with_spaces_around(): void
    {
        $html = '<a href="  https://example.com/path  ">Tautan</a>';

        $this->assertStringContainsString('https://example.com/path', HtmlSanitizer::sanitize($html));
    }

    public function test_strips_css_escaped_unsafe_values(): void
    {
        $html = '<p style="color: red; background: u\72l(javascript:alert(1))">Teks</p>'
            .'<p style="width: e\78pression(alert(1))">Teks2</p>'
            .'<p style="color: red">Teks3</p>';

        $this->assertSame(
            '<p style="color: red">Teks</p><p>Teks2</p><p style="color: red">Teks3</p>',
            HtmlSanitizer::sanitize($html)
        );
    }
}
