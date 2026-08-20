<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_tracking_page(): void
    {
        $submission = Submission::factory()->create([
            'token' => 'test-token-123',
            'nama_pemohon' => 'Ahmad Fauzi',
            'status' => Submission::STATUS_BARU,
        ]);

        $this->get(route('permohonan.track', 'test-token-123'))
            ->assertOk()
            ->assertSee('Ahmad Fauzi')
            ->assertSee('Status Permohonan')
            ->assertSee('Baru');
    }

    public function test_tracking_shows_correct_status(): void
    {
        Submission::factory()->create([
            'token' => 'token-diproses',
            'status' => Submission::STATUS_DIPROSES,
        ]);

        $this->get(route('permohonan.track', 'token-diproses'))
            ->assertOk()
            ->assertSee('Diproses')
            ->assertSee('Saat Ini');
    }

    public function test_tracking_shows_rejected_status(): void
    {
        Submission::factory()->create([
            'token' => 'token-ditolak',
            'status' => Submission::STATUS_DITOLAK,
            'catatan' => 'Data tidak lengkap',
        ]);

        $this->get(route('permohonan.track', 'token-ditolak'))
            ->assertOk()
            ->assertSee('Permohonan Ditolak')
            ->assertSee('Data tidak lengkap');
    }

    public function test_tracking_invalid_token_returns_404(): void
    {
        $this->get(route('permohonan.track', 'invalid-token-xyz'))
            ->assertNotFound();
    }

    public function test_tracking_shows_letter_type(): void
    {
        $submission = Submission::factory()->create([
            'token' => 'token-with-type',
        ]);

        $this->get(route('permohonan.track', 'token-with-type'))
            ->assertOk()
            ->assertSee($submission->letterType->name);
    }
}
