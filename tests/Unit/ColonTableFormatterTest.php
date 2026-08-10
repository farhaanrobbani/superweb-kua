<?php

namespace Tests\Unit;

use App\Support\ColonTableFormatter;
use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class ColonTableFormatterTest extends TestCase
{
    public function test_plain_text_colon_rows_become_aligned_table(): void
    {
        $body = ColonTableFormatter::format(HtmlSanitizer::toHtml(
            "Pembuka surat.\n\nNama : [nama]\nTempat, Tgl Lahir : [ttl]\nNIK : [nik]\nAlamat : [alamat]\n\nPenutup."
        ));

        $this->assertStringContainsString('<table', $body);
        $this->assertStringContainsString('Nama</td><td style="width:14px;text-align:left;vertical-align:top">:</td>', $body);
        $this->assertStringContainsString('<p>Pembuka surat.</p>', $body);
        $this->assertStringContainsString('<p>Penutup.</p>', $body);
        $this->assertSame(4, substr_count($body, '<tr>'));
    }

    public function test_prose_ending_with_colon_is_not_transformed(): void
    {
        $body = ColonTableFormatter::format(
            '<p>Yang bertanda tangan di bawah ini, Kepala Kantor Urusan Agama Kecamatan [kecamatan] menerangkan bahwa:</p>'
        );

        $this->assertStringNotContainsString('<table', $body);
        $this->assertStringContainsString('menerangkan bahwa:</p>', $body);
    }

    public function test_html_rows_merge_into_single_table_and_preserve_content(): void
    {
        $body = ColonTableFormatter::format(
            "<p style=\"padding-left: 40px\">Nama lengkap&nbsp;&nbsp;&nbsp;&nbsp;: <strong>[nama_pasangan1] </strong>---</p>\n"
            ."<p style=\"padding-left: 40px\">Bin : [wali1]</p>\n"
            ."<p style=\"padding-left: 40px\">Agama : [agama1]</p>"
        );

        $this->assertSame(1, substr_count($body, '<table'));
        $this->assertStringContainsString('margin-left:40px', $body);
        $this->assertStringContainsString('<strong>[nama_pasangan1] </strong>---', $body);
        $this->assertStringContainsString('[wali1]</td>', $body);
        $this->assertStringNotContainsString('Nama lengkap&nbsp;', $body);
    }

    public function test_trailing_break_line_does_not_break_transformation(): void
    {
        $body = ColonTableFormatter::format('<p>Nama : [nama]<br>Nomor Akta : [no_akta]<br></p>');

        $this->assertStringContainsString('<table', $body);
        $this->assertSame(2, substr_count($body, '<tr>'));
    }

    public function test_single_line_colon_paragraph_is_transformed(): void
    {
        $body = ColonTableFormatter::format('<p>Alamat : [alamat]</p>');

        $this->assertStringContainsString('<table', $body);
    }

    public function test_mid_sentence_colon_is_not_transformed(): void
    {
        $body = ColonTableFormatter::format(
            '<p>Pasangan telah menikah sah pada tanggal [tanggal_akta] Dengan nomor Akta Nikah: [no_akta].</p>'
        );

        $this->assertStringNotContainsString('<table', $body);
        $this->assertStringContainsString('Akta Nikah: [no_akta].', $body);
    }
}
