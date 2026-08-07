<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $kepala;

    private User $operator;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kepala = User::factory()->create(['role' => User::ROLE_KEPALA]);
        $this->operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $this->staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public static function staffAccessibleRoutes(): array
    {
        return [
            'dashboard' => ['dashboard'],
            'surat' => ['letters.index'],
            'permohonan' => ['submissions.index'],
            'kritik dan saran' => ['kritik-saran.index'],
        ];
    }

    public static function staffForbiddenRoutes(): array
    {
        return [
            'jenis surat' => ['letter-types.index'],
            'template' => ['letter-templates.index'],
            'navbar' => ['navbar.index'],
            'pengumuman' => ['announcements.index'],
            'download center' => ['download-items.index'],
            'page' => ['pages.index'],
            'daftar staf' => ['staff.index'],
            'pengaturan web' => ['kua-settings.edit'],
            'akun' => ['users.index'],
        ];
    }

    public function test_staff_can_access_dashboard_letters_submissions_and_kritik_saran(): void
    {
        foreach (self::staffAccessibleRoutes() as $label => [$route]) {
            $this->actingAs($this->staff)
                ->get(route($route))
                ->assertOk("Staf harus dapat akses $label");
        }
    }

    public function test_staff_cannot_access_restricted_menus(): void
    {
        foreach (self::staffForbiddenRoutes() as $label => [$route]) {
            $this->actingAs($this->staff)
                ->get(route($route))
                ->assertForbidden("Staf tidak boleh akses $label");
        }
    }

    public function test_operator_can_access_all_manageable_menus(): void
    {
        $menus = array_merge(self::staffAccessibleRoutes(), self::staffForbiddenRoutes());
        unset($menus['akun']);

        foreach ($menus as $label => [$route]) {
            $this->actingAs($this->operator)
                ->get(route($route))
                ->assertOk("Operator harus dapat akses $label");
        }
    }

    public function test_operator_cannot_access_user_management(): void
    {
        $this->actingAs($this->operator)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_operator_cannot_approve_or_reject_letters(): void
    {
        $letter = Letter::factory()->create(['status' => Letter::STATUS_DIAJUKAN]);

        $this->actingAs($this->operator)
            ->post(route('letters.setujui', $letter))
            ->assertForbidden();
    }

    public function test_kepala_can_access_all_menus(): void
    {
        $menus = array_merge(self::staffAccessibleRoutes(), self::staffForbiddenRoutes());

        foreach ($menus as $label => [$route]) {
            $this->actingAs($this->kepala)
                ->get(route($route))
                ->assertOk("Kepala harus dapat akses $label");
        }
    }

    public function test_kepala_can_approve_letters(): void
    {
        $letter = Letter::factory()->create(['status' => Letter::STATUS_DIAJUKAN]);

        $this->actingAs($this->kepala)
            ->post(route('letters.setujui', $letter))
            ->assertRedirect();
    }

    public function test_sidebar_hides_restricted_menus_for_staff(): void
    {
        $this->actingAs($this->staff)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Pengumuman')
            ->assertDontSee('Download Center')
            ->assertDontSee('Daftar Staf')
            ->assertDontSee('Jenis Surat')
            ->assertDontSee('Template')
            ->assertSee('Kritik & Saran');
    }

    public function test_sidebar_shows_all_menus_for_operator(): void
    {
        $this->actingAs($this->operator)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pengumuman')
            ->assertSee('Download Center')
            ->assertSee('Daftar Staf')
            ->assertSee('Jenis Surat')
            ->assertSee('Pengaturan Web');
    }
}
