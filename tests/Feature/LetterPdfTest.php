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
}
