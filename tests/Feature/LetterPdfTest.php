<?php

namespace Tests\Feature;

use App\Models\KuaSetting;
use App\Models\Letter;
use App\Models\LetterTemplate;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LetterPdfTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private LetterType $type;
    private Letter $letter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);

        $this->type = LetterType::factory()->create([
            'code' => 'SKU',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'text', 'required' => false],
            ],
        ]);

        LetterTemplate::factory()->create([
            'letter_type_id' => $this->type->id,
            'body' => "Menerangkan bahwa [nama] beralamat di [alamat], [kecamatan].\nNomor surat [nomor], tanggal [tanggal_surat].",
        ]);

        $this->letter = Letter::create([
            'letter_type_id' => $this->type->id,
            'nomor' => 'SKU.001/KUA.VIII/2026',
            'tanggal_surat' => now(),
            'perihal' => 'Keterangan Domisili',
            'data' => ['nama' => 'Budi <b>Santoso</b>', 'alamat' => 'Jl. Merdeka 1'],
            'status' => Letter::STATUS_TERBIT,
            'created_by' => $this->user->id,
        ]);

        KuaSetting::set('kecamatan', 'Bogor');
        KuaSetting::set('kabupaten', 'Bogor');
        KuaSetting::set('kepala_nama', 'H. Kepala KUA');
        KuaSetting::set('kepala_nip', '197001011990011001');
    }

    public function test_pdf_only_available_for_published_letters(): void
    {
        $draft = Letter::create([
            'letter_type_id' => $this->type->id,
            'perihal' => 'Draft',
            'data' => ['nama' => 'A', 'alamat' => 'B'],
            'status' => Letter::STATUS_DRAFT,
            'created_by' => $this->user->id,
        ]);

        $this->actingAs($this->user)->get(route('letters.pdf', $draft))->assertForbidden();
    }

    public function test_published_letter_downloads_pdf(): void
    {
        $this->actingAs($this->user)
            ->get(route('letters.pdf', $this->letter))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_includes_logo_when_uploaded(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png', 200, 200)->store('logos', 'public');
        KuaSetting::set('logo_path', $path);

        $response = $this->actingAs($this->user)->get(route('letters.pdf', $this->letter));

        $response->assertOk();
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringContainsString('/Subtype /Image', $content);
    }

    public function test_pdf_includes_second_logo_when_selected(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo2.png', 200, 200)->store('logos2', 'public');
        KuaSetting::set('logo2_path', $path);
        KuaSetting::set('kop_logo', 'logo2');

        $response = $this->actingAs($this->user)->get(route('letters.pdf', $this->letter));

        $response->assertOk();
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringContainsString('/Subtype /Image', $content);
    }

    public function test_pdf_falls_back_to_logo1_when_logo2_selected_but_missing(): void
    {
        Storage::fake('public');

        KuaSetting::set('kop_logo', 'logo2');

        $response = $this->actingAs($this->user)->get(route('letters.pdf', $this->letter));

        $response->assertOk();
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringNotContainsString('/Subtype /Image', $content);
    }

    public function test_pdf_downloads_with_anchor_hidden(): void
    {
        Storage::fake('public');

        KuaSetting::set('kop_anchor', '0');

        $this->actingAs($this->user)
            ->get(route('letters.pdf', $this->letter))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_downloads_with_custom_kop_text(): void
    {
        Storage::fake('public');

        KuaSetting::set('kop_teks', "#KUA KECAMATAN CONTOH\n##KECAMATAN CONTOH KABUPATEN CONTOH\n###KECAMATAN SEKSI\nJl. Contoh No. 1");
        KuaSetting::set('kop_ukuran_judul', '20');
        KuaSetting::set('kop_ukuran_sub', '14');
        KuaSetting::set('kop_ukuran_sub2', '12');
        KuaSetting::set('kop_ukuran_baris', '11');

        $this->actingAs($this->user)
            ->get(route('letters.pdf', $this->letter))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_render_body_replaces_placeholders_and_escapes_html(): void
    {
        $body = $this->letter->renderBody();

        $this->assertStringContainsString('Budi &lt;b&gt;Santoso&lt;/b&gt;', $body);
        $this->assertStringContainsString('Jl. Merdeka 1', $body);
        $this->assertStringContainsString('Bogor', $body);
        $this->assertStringContainsString('SKU.001/KUA.VIII/2026', $body);
        $this->assertStringNotContainsString('<b>', $body);
    }

    public function test_render_body_uses_indonesian_date(): void
    {
        $this->letter->update(['tanggal_surat' => '2026-08-05']);
        $this->assertStringContainsString('05 Agustus 2026', $this->letter->renderBody());
    }

    public function test_render_body_preserves_html_in_template_and_strips_script(): void
    {
        LetterTemplate::where('letter_type_id', $this->type->id)->update(['active' => false]);
        LetterTemplate::factory()->create([
            'letter_type_id' => $this->type->id,
            'name' => 'Template HTML',
            'active' => true,
            'body' => '<script></script><p>Dengan <strong>hormat</strong>, kami terangkan bahwa [nama].</p>',
        ]);

        $body = $this->letter->renderBody();

        $this->assertStringContainsString('<p>Dengan <strong>hormat</strong>, kami terangkan bahwa Budi &lt;b&gt;Santoso&lt;/b&gt;.</p>', $body);
        $this->assertStringNotContainsString('script', $body);
    }

    public function test_render_body_wraps_plain_text_in_paragraphs(): void
    {
        $body = $this->letter->renderBody();

        $this->assertStringContainsString('<p>Menerangkan bahwa Budi &lt;b&gt;Santoso&lt;/b&gt; beralamat di Jl. Merdeka 1, Bogor.', $body);
        $this->assertStringContainsString('Nomor surat SKU.001/KUA.VIII/2026, tanggal 06 Agustus 2026.</p>', $body);
    }
}
