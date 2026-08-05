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
            'code' => 'SKU',
            'name' => 'Surat Keterangan Umum',
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
            ->assertSee('Surat Keterangan Umum');
    }

    public function test_public_can_submit_application(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SKU',
            'nama_pemohon' => 'Andi',
            'kontak' => '08123456789',
            'data' => ['nama' => 'Andi Setiawan'],
        ])->assertRedirect(route('permohonan.sukses'));

        $this->assertDatabaseHas('submissions', [
            'nama_pemohon' => 'Andi',
            'status' => 'baru',
        ]);
    }

    public function test_public_submission_requires_contact(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SKU',
            'nama_pemohon' => 'Andi',
            'data' => ['nama' => 'Andi'],
        ])->assertSessionHasErrors('kontak');
    }

    public function test_honeypot_blocks_bot_submissions(): void
    {
        $this->post(route('permohonan.store'), [
            'jenis' => 'SKU',
            'nama_pemohon' => 'Bot',
            'kontak' => 'x',
            'website' => 'http://spam.example',
            'data' => ['nama' => 'Bot'],
        ])->assertForbidden();
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
                'jenis' => 'SKU',
                'dari' => $submission->id,
            ]));
    }

    public function test_guest_cannot_access_admin_submissions(): void
    {
        $this->get(route('submissions.index'))->assertRedirect(route('login'));
    }
}
