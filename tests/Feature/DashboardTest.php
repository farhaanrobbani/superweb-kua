<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\LetterType;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_statistics(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_STAFF]);
        $type = LetterType::factory()->create();

        Letter::factory()->count(3)->create(['letter_type_id' => $type->id, 'status' => Letter::STATUS_TERBIT]);
        Letter::factory()->create(['letter_type_id' => $type->id, 'status' => Letter::STATUS_DIAJUKAN]);
        Submission::factory()->create(['letter_type_id' => $type->id, 'status' => Submission::STATUS_BARU]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total Surat')
            ->assertSee('Permohonan Baru');
    }
}
