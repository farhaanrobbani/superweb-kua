<?php

namespace Tests\Feature;

use App\Models\LetterType;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;
    private LetterType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $this->type = LetterType::factory()->create([
            'code' => 'SPD',
            'name' => 'Surat Permohonan Duplikat Akta Nikah',
            'publik' => true,
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ],
        ]);
    }

    public function test_public_can_view_submission_form(): void
    {
        $this->get(route('permohonan.create'))
            ->assertOk()
            ->assertSee('Form Permohonan Surat')
            ->assertSee('Surat Permohonan Duplikat Akta Nikah');
    }

    public function test_public_can_submit_application(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Andi',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
        ])->assertRedirect(route('permohonan.sukses'));

        $this->assertDatabaseHas('submissions', [
            'nama_pemohon' => 'Andi',
            'status' => 'baru',
        ]);
    }

    public function test_public_submission_generates_download_token(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Andi',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
        ])->assertRedirect(route('permohonan.sukses'));

        $submission = Submission::where('nama_pemohon', 'Andi')->firstOrFail();

        $this->assertNotNull($submission->token);
        $this->assertEquals(40, strlen($submission->token));
    }

    public function test_sukses_page_shows_download_button_after_submit(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Andi',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
        ])->assertRedirect(route('permohonan.sukses'));

        $this->get(route('permohonan.sukses'))
            ->assertOk()
            ->assertSee('Download Surat Permohonan (PDF)');

        $this->get(route('permohonan.sukses'))
            ->assertOk()
            ->assertSee('Download Surat Permohonan (PDF)');
    }

    public function test_sukses_page_hides_download_button_without_session(): void
    {
        $this->get(route('permohonan.sukses'))
            ->assertOk()
            ->assertDontSee('Download Surat Permohonan (PDF)');
    }

    public function test_public_can_download_permohonan_pdf_with_token(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi Setiawan',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
            'status' => Submission::STATUS_BARU,
            'token' => 'token-valid-contoh',
        ]);

        $this->get(route('permohonan.download', 'token-valid-contoh'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_public_cannot_download_permohonan_with_unknown_token(): void
    {
        $this->get(route('permohonan.download', 'token-tidak-ada'))
            ->assertNotFound();
    }

    public function test_public_submission_requires_contact(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Andi',
            'data' => ['nama' => 'Andi'],
        ])->assertSessionHasErrors('kontak');
    }

    public function test_honeypot_blocks_bot_submissions(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Bot',
            'kontak' => 'x',
            'website' => 'http://spam.example',
            'data' => ['nama' => 'Bot'],
        ])->assertForbidden();
    }

    public function test_public_form_hides_internal_fields(): void
    {
        $this->type->update(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => false, 'internal' => true],
        ]]);

        $this->get(route('permohonan.create', ['jenis' => 'SPD']))
            ->assertOk()
            ->assertSee('Nama')
            ->assertDontSee('Catatan Petugas');
    }

    public function test_public_submission_ignores_internal_fields(): void
    {
        $this->type->update(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => true, 'internal' => true],
        ]]);

        $this->post(route('permohonan.store'), [
            'jenis' => 'SPD',
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
        ])->assertRedirect(route('permohonan.sukses'));

        $submission = Submission::where('nama_pemohon', 'Andi')->firstOrFail();
        $this->assertArrayNotHasKey('catatan_petugas', $submission->data);
    }

    public function test_permohonan_fields_excludes_internal_fields(): void
    {
        $this->type->update([
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => false, 'internal' => true],
            ],
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $names = array_column($submission->permohonanFields(), 'name');
        $this->assertSame(['nama'], $names);
    }

    public function test_staff_can_view_submission_list(): void
    {
        Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->actingAs($this->staff)
            ->get(route('submissions.index'))
            ->assertOk()
            ->assertSee('Andi');
    }

    public function test_staff_can_update_submission_status(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->actingAs($this->staff)
            ->put(route('submissions.update', $submission), [
                'status' => Submission::STATUS_DIPROSES,
                'catatan' => '',
            ])->assertRedirect();

        $this->assertDatabaseHas('submissions', ['id' => $submission->id, 'status' => 'diproses']);
    }

    public function test_create_letter_from_submission_prefills_data(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi Setiawan'],
            'status' => Submission::STATUS_DIPROSES,
        ]);

        $this->actingAs($this->staff)
            ->post(route('submissions.buat-surat', $submission))
            ->assertRedirect(route('letters.create', [
                'jenis' => 'SPD',
                'dari' => $submission->id,
            ]));
    }

    public function test_public_form_hides_admin_only_types(): void
    {
        LetterType::factory()->create([
            'code' => 'SKU',
            'name' => 'Surat Keterangan Umum',
            'publik' => false,
        ]);

        $this->get(route('permohonan.create'))
            ->assertOk()
            ->assertSee('Surat Permohonan Duplikat Akta Nikah')
            ->assertDontSee('Surat Keterangan Umum');
    }

    public function test_public_cannot_submit_admin_only_type(): void
    {
        LetterType::factory()->create([
            'code' => 'SKU',
            'name' => 'Surat Keterangan Umum',
            'publik' => false,
        ]);

        $this->post(route('permohonan.store'), [
            'jenis' => 'SKU',
            'nama_pemohon' => 'Andi',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi'],
        ])->assertNotFound();

        $this->assertDatabaseMissing('submissions', ['nama_pemohon' => 'Andi']);
    }

    public function test_guest_cannot_access_admin_submissions(): void
    {
        $this->get(route('submissions.index'))->assertRedirect(route('login'));
    }

    public function test_submission_show_displays_cetak_surat_permohonan_button(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->actingAs($this->staff)
            ->get(route('submissions.show', $submission))
            ->assertOk()
            ->assertSee('Cetak Surat Permohonan (arsip)');
    }

    public function test_staff_can_download_surat_permohonan_pdf(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi Setiawan',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->actingAs($this->staff)
            ->get(route('submissions.cetak-permohonan', $submission))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_guest_cannot_download_surat_permohonan_pdf(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->get(route('submissions.cetak-permohonan', $submission))
            ->assertRedirect(route('login'));
    }

    public function test_permohonan_fields_filters_identity_fields(): void
    {
        $this->type->update([
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => true],
            ],
            'permohonan_fields' => ['nama'],
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi', 'nik' => '123'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->assertSame(['nama'], array_column($submission->permohonanFields(), 'name'));

        $this->type->update(['permohonan_fields' => null]);
        $submission->unsetRelation('letterType');
        $this->assertSame(
            ['nama', 'nik'],
            array_column($submission->permohonanFields(), 'name')
        );
    }

    public function test_identity_value_formats_dates_and_falls_back(): void
    {
        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['tanggal_akta' => '2024-01-15', 'nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $this->assertSame('15 Januari 2024', $submission->identityValue('tanggal_akta'));
        $this->assertSame('Andi', $submission->identityValue('nama'));
        $this->assertSame('—', $submission->identityValue('tidak_ada'));
    }

    public function test_permohonan_paragraphs_splits_narrative(): void
    {
        $this->type->update([
            'permohonan_body' => "Memohon agar diterbitkan Surat atas nama [nama].\r\nDuplikat akan digunakan untuk keperluan.\n\nDemikian Surat Permohonan ini kami buat.",
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $paragraphs = $submission->permohonanParagraphs();

        $this->assertCount(3, $paragraphs);
        $this->assertStringContainsString('Memohon agar diterbitkan', $paragraphs[0]);
        $this->assertStringContainsString('Duplikat akan digunakan', $paragraphs[1]);
        $this->assertStringContainsString('Demikian Surat Permohonan', $paragraphs[2]);
    }

    public function test_render_permohonan_body_replaces_tokens(): void
    {
        $this->type->update([
            'permohonan_body' => "Memohon agar diterbitkan Surat atas nama [nama].\n\nDemikian Surat Permohonan ini kami buat.",
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi Setiawan',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi Setiawan'],
            'status' => Submission::STATUS_BARU,
        ]);

        $body = $submission->renderPermohonanBody();

        $this->assertStringContainsString('Surat atas nama Andi Setiawan', $body);
        $this->assertStringContainsString('Demikian Surat Permohonan', $body);
        $this->assertStringNotContainsString('[nama]', $body);
    }

    public function test_render_permohonan_body_formats_dates(): void
    {
        $this->type->update([
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'tanggal_akta', 'label' => 'Tanggal Akta', 'type' => 'date', 'required' => true],
            ],
            'permohonan_body' => 'Akta diterbitkan pada [tanggal_akta] atas nama [nama].',
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi Setiawan',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi Setiawan', 'tanggal_akta' => '2024-01-15'],
            'status' => Submission::STATUS_BARU,
        ]);

        $body = $submission->renderPermohonanBody();

        $this->assertStringContainsString('Akta diterbitkan pada 15 Januari 2024', $body);
    }

    public function test_render_permohonan_body_falls_back_to_generic(): void
    {
        $this->type->update(['permohonan_body' => null]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $body = $submission->renderPermohonanBody();

        $this->assertStringContainsString('Memohon agar diterbitkan ' . $this->type->name, $body);
        $this->assertStringContainsString('Demikian Surat Permohonan', $body);
    }

    public function test_render_permohonan_body_preserves_html_and_strips_script(): void
    {
        $this->type->update([
            'permohonan_body' => '<script></script><p>Memohon atas nama <strong>[nama]</strong>.</p>',
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi'],
            'status' => Submission::STATUS_BARU,
        ]);

        $body = $submission->renderPermohonanBody();

        $this->assertStringContainsString('<p>Memohon atas nama <strong>Andi</strong>.</p>', $body);
        $this->assertStringNotContainsString('script', $body);
        $this->assertStringNotContainsString('[nama]', $body);
    }

    public function test_render_permohonan_body_escapes_html_in_values(): void
    {
        $this->type->update([
            'permohonan_body' => '<p>Atas nama [nama].</p>',
        ]);

        $submission = Submission::create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Andi',
            'kontak' => '0812',
            'data' => ['nama' => 'Andi <b>Setiawan</b>'],
            'status' => Submission::STATUS_BARU,
        ]);

        $body = $submission->renderPermohonanBody();

        $this->assertStringContainsString('Andi &lt;b&gt;Setiawan&lt;/b&gt;', $body);
        $this->assertStringNotContainsString('<b>Setiawan</b>', $body);
    }
}
