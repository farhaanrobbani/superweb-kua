<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LetterWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;
    private User $kepala;
    private LetterType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $this->kepala = User::factory()->create(['role' => User::ROLE_KEPALA]);
        $this->type = LetterType::factory()->create([
            'code' => 'SKU',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'text', 'required' => false],
            ],
        ]);
    }

    private function draftLetter(): Letter
    {
        return Letter::create([
            'letter_type_id' => $this->type->id,
            'perihal' => 'Permohonan Surat Keterangan',
            'data' => ['nama' => 'Budi', 'alamat' => 'Jl. Merdeka'],
            'status' => Letter::STATUS_DRAFT,
            'created_by' => $this->staff->id,
        ]);
    }

    public function test_staff_can_create_letter_draft(): void
    {
        $response = $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'perihal' => 'Permohonan SK',
                'data' => ['nama' => 'Budi', 'alamat' => 'Jl. Merdeka'],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('letters', ['perihal' => 'Permohonan SK', 'status' => 'draft']);
    }

    public function test_staff_can_create_letter_draft_with_manual_number(): void
    {
        $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'nomor' => '001/KUA.10.02.07/VIII/2026',
                'tanggal_surat' => '2026-08-05',
                'perihal' => 'Permohonan SK',
                'data' => ['nama' => 'Budi', 'alamat' => 'Jl. Merdeka'],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('letters', [
            'perihal' => 'Permohonan SK',
            'status' => 'draft',
            'nomor' => '001/KUA.10.02.07/VIII/2026',
            'tanggal_surat' => '2026-08-05 00:00:00',
        ]);
    }

    public function test_required_field_is_validated(): void
    {
        $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'perihal' => 'Tanpa Nama',
                'data' => ['nama' => '', 'alamat' => 'Jl. X'],
            ])
            ->assertSessionHasErrors('data.nama');
    }

    public function test_letter_create_form_shows_internal_fields(): void
    {
        $this->type->update(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => false, 'internal' => true],
        ]]);

        $this->actingAs($this->staff)
            ->get(route('letters.create', ['jenis' => 'SKU']))
            ->assertOk()
            ->assertSee('Catatan Petugas')
            ->assertSee('Data tambahan (diisi petugas)');
    }

    public function test_internal_required_field_is_validated_on_letter(): void
    {
        $this->type->update(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => true, 'internal' => true],
        ]]);

        $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'perihal' => 'Tanpa catatan',
                'data' => ['nama' => 'Budi', 'catatan_petugas' => ''],
            ])
            ->assertSessionHasErrors('data.catatan_petugas');
    }

    public function test_internal_field_data_is_saved_on_letter(): void
    {
        $this->type->update(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'catatan_petugas', 'label' => 'Catatan Petugas', 'type' => 'text', 'required' => false, 'internal' => true],
        ]]);

        $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'perihal' => 'Permohonan SK',
                'data' => ['nama' => 'Budi', 'catatan_petugas' => 'Sudah diverifikasi'],
            ])
            ->assertRedirect();

        $letter = Letter::where('perihal', 'Permohonan SK')->firstOrFail();
        $this->assertSame('Sudah diverifikasi', $letter->data['catatan_petugas']);
    }

    public function test_staff_can_submit_letter_for_approval(): void
    {
        $letter = $this->draftLetter();

        $this->actingAs($this->staff)
            ->post(route('letters.ajukan', $letter))
            ->assertRedirect();

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'status' => 'diajukan']);
    }

    public function test_kepala_can_approve_letter(): void
    {
        $letter = $this->draftLetter();
        $letter->update(['status' => Letter::STATUS_DIAJUKAN]);

        $this->actingAs($this->kepala)
            ->post(route('letters.setujui', $letter))
            ->assertRedirect();

        $this->assertDatabaseHas('letters', [
            'id' => $letter->id,
            'status' => 'disetujui',
            'approved_by' => $this->kepala->id,
        ]);
    }

    public function test_staff_cannot_approve_letter(): void
    {
        $letter = $this->draftLetter();
        $letter->update(['status' => Letter::STATUS_DIAJUKAN]);

        $this->actingAs($this->staff)
            ->post(route('letters.setujui', $letter))
            ->assertForbidden();
    }

    public function test_kepala_can_reject_letter(): void
    {
        $letter = $this->draftLetter();
        $letter->update(['status' => Letter::STATUS_DIAJUKAN]);

        $this->actingAs($this->kepala)
            ->post(route('letters.tolak', $letter), ['keterangan' => 'Data tidak lengkap'])
            ->assertRedirect();

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'status' => 'ditolak']);
    }

    public function test_publish_requires_nomor(): void
    {
        $letter = $this->draftLetter();
        $letter->update(['status' => Letter::STATUS_DISETUJUI]);

        $this->actingAs($this->staff)
            ->post(route('letters.terbitkan', $letter))
            ->assertSessionHasErrors('nomor');

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'status' => 'disetujui']);
    }

    public function test_publish_requires_tanggal_surat(): void
    {
        $letter = $this->draftLetter();
        $letter->update(['status' => Letter::STATUS_DISETUJUI, 'nomor' => '001/KUA.10.02.07/VIII/2026']);

        $this->actingAs($this->staff)
            ->post(route('letters.terbitkan', $letter))
            ->assertSessionHasErrors('tanggal_surat');

        $this->assertDatabaseHas('letters', ['id' => $letter->id, 'status' => 'disetujui']);
    }

    public function test_publish_uses_manual_number_and_date(): void
    {
        $letter = $this->draftLetter();
        $letter->update([
            'status' => Letter::STATUS_DISETUJUI,
            'nomor' => '001/KUA.10.02.07/VIII/2026',
            'tanggal_surat' => '2026-08-05',
        ]);

        $this->actingAs($this->staff)
            ->post(route('letters.terbitkan', $letter))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('letters', [
            'id' => $letter->id,
            'status' => 'terbit',
            'nomor' => '001/KUA.10.02.07/VIII/2026',
            'tanggal_surat' => '2026-08-05 00:00:00',
        ]);
    }

    public function test_letter_create_prefills_data_from_submission(): void
    {
        $submission = Submission::factory()->create([
            'letter_type_id' => $this->type->id,
            'nama_pemohon' => 'Budi Santoso',
            'kontak' => '081234567890',
            'data' => ['nama' => 'Budi Santoso', 'alamat' => 'Jl. Merdeka No. 1'],
        ]);

        $this->actingAs($this->staff)
            ->get(route('letters.create', ['jenis' => 'SKU', 'dari' => $submission->id]))
            ->assertOk()
            ->assertSee('Data diisi otomatis dari permohonan')
            ->assertSee('Budi Santoso')
            ->assertSee('Permohonan Penerbitan ' . $this->type->name)
            ->assertSee('value="Budi Santoso"', false);
    }

    public function test_letter_create_without_submission_is_empty(): void
    {
        $this->actingAs($this->staff)
            ->get(route('letters.create', ['jenis' => 'SKU']))
            ->assertOk()
            ->assertDontSee('Data diisi otomatis dari permohonan')
            ->assertDontSee('value="Budi Santoso"', false);
    }

    public function test_letter_store_from_submission_marks_submission_diproses(): void
    {
        $submission = Submission::factory()->create([
            'letter_type_id' => $this->type->id,
            'data' => ['nama' => 'Budi', 'alamat' => 'Jl. X'],
        ]);

        $this->actingAs($this->staff)
            ->post(route('letters.store'), [
                'jenis' => 'SKU',
                'perihal' => 'Permohonan SK',
                'data' => ['nama' => 'Budi', 'alamat' => 'Jl. X'],
                'dari' => $submission->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('letters', ['perihal' => 'Permohonan SK', 'status' => 'draft']);
        $this->assertDatabaseHas('submissions', ['id' => $submission->id, 'status' => 'diproses']);
    }

    public function test_letter_list_is_searchable(): void
    {
        $this->draftLetter();

        $this->actingAs($this->staff)
            ->get(route('letters.index', ['cari' => 'Permohonan']))
            ->assertOk()
            ->assertSee('Permohonan Surat Keterangan');
    }
}
