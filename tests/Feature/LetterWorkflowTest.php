<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\LetterType;
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

    public function test_publish_generates_sequential_number(): void
    {
        $first = $this->draftLetter();
        $first->update(['status' => Letter::STATUS_DISETUJUI]);
        $this->actingAs($this->staff)->post(route('letters.terbitkan', $first));

        $second = $this->draftLetter();
        $second->update(['status' => Letter::STATUS_DISETUJUI]);
        $this->actingAs($this->staff)->post(route('letters.terbitkan', $second));

        $this->assertDatabaseHas('letters', ['id' => $first->id, 'nomor' => 'SKU.001/KUA.VIII/2026']);
        $this->assertDatabaseHas('letters', ['id' => $second->id, 'nomor' => 'SKU.002/KUA.VIII/2026']);
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
