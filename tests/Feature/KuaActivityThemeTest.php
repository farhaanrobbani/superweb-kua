<?php

namespace Tests\Feature;

use App\Models\KuaActivityTheme;
use App\Models\KuaDailyData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KuaActivityThemeTest extends TestCase
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

    public function test_operator_can_access_theme_index(): void
    {
        $this->actingAs($this->operator)
            ->get(route('kua-themes.index'))
            ->assertOk()
            ->assertSee('Tema Pekerjaan');
    }

    public function test_staff_cannot_access_theme_pages(): void
    {
        $this->actingAs($this->staff)
            ->get(route('kua-themes.index'))
            ->assertForbidden();

        $this->actingAs($this->staff)
            ->post(route('kua-themes.store'), ['label' => 'Tema Baru'])
            ->assertForbidden();
    }

    public function test_operator_can_create_theme(): void
    {
        $this->actingAs($this->operator)
            ->post(route('kua-themes.store'), [
                'label' => 'Pelayanan Kursus Pranikah',
                'sort_order' => 11,
            ])
            ->assertRedirect(route('kua-themes.index'));

        $this->assertDatabaseHas('kua_activity_themes', [
            'key' => 'pelayanan_kursus_pranikah',
            'label' => 'Pelayanan Kursus Pranikah',
            'active' => true,
            'sort_order' => 11,
        ]);
    }

    public function test_theme_key_must_be_unique(): void
    {
        KuaActivityTheme::create(['key' => 'duplikat', 'label' => 'Duplikat', 'sort_order' => 50]);

        $this->actingAs($this->operator)
            ->post(route('kua-themes.store'), [
                'label' => 'Duplikat lain',
                'key' => 'duplikat',
            ])
            ->assertSessionHasErrors('key');

        $this->assertDatabaseMissing('kua_activity_themes', ['label' => 'Duplikat lain']);
    }

    public function test_theme_key_auto_suffixed_when_slug_collides(): void
    {
        KuaActivityTheme::create(['key' => 'kursus', 'label' => 'Kursus', 'sort_order' => 1]);

        $this->actingAs($this->operator)
            ->post(route('kua-themes.store'), ['label' => 'Kursus', 'key' => '']);

        $this->assertDatabaseHas('kua_activity_themes', ['key' => 'kursus_2']);
    }

    public function test_inactive_theme_is_hidden_from_master_form(): void
    {
        KuaActivityTheme::query()->update(['active' => false]);

        $this->actingAs($this->operator)
            ->post(route('kua-daily.store'), [
                'tanggal' => '2026-08-03',
                'pendaftaran_nikah_kantor' => 4,
            ])
            ->assertRedirect();

        $record = KuaDailyData::where('tanggal', '2026-08-03')->first();
        $this->assertNotNull($record);
        $this->assertArrayNotHasKey('pendaftaran_nikah_kantor', $record->data);
    }

    public function test_deleting_theme_purges_values_from_daily_data(): void
    {
        $theme = KuaActivityTheme::create(['key' => 'rapat_internal', 'label' => 'Rapat Internal', 'sort_order' => 99]);

        KuaDailyData::create([
            'tanggal' => '2026-08-03',
            'data' => ['rapat_internal' => 3, 'pelaksanaan_bimwin' => 2],
            'created_by' => $this->operator->id,
        ]);

        $this->actingAs($this->operator)
            ->delete(route('kua-themes.destroy', $theme))
            ->assertRedirect(route('kua-themes.index'));

        $this->assertDatabaseMissing('kua_activity_themes', ['id' => $theme->id]);

        $record = KuaDailyData::where('tanggal', '2026-08-03')->first();
        $this->assertArrayNotHasKey('rapat_internal', $record->data);
        $this->assertSame(2, $record->data['pelaksanaan_bimwin']);
    }

    public function test_operator_can_update_theme(): void
    {
        $theme = KuaActivityTheme::create(['key' => 'rapat', 'label' => 'Rapat', 'sort_order' => 1]);

        $this->actingAs($this->operator)
            ->put(route('kua-themes.update', $theme), [
                'label' => 'Rapat Koordinasi',
                'key' => 'rapat_koordinasi',
                'sort_order' => 5,
                'active' => '0',
            ])
            ->assertRedirect(route('kua-themes.index'));

        $this->assertDatabaseHas('kua_activity_themes', [
            'id' => $theme->id,
            'label' => 'Rapat Koordinasi',
            'key' => 'rapat_koordinasi',
            'sort_order' => 5,
            'active' => false,
        ]);
    }

    public function test_theme_can_be_moved_up(): void
    {
        $first = KuaActivityTheme::create(['key' => 'tema_a', 'label' => 'Tema A', 'sort_order' => 50]);
        $second = KuaActivityTheme::create(['key' => 'tema_b', 'label' => 'Tema B', 'sort_order' => 51]);

        $this->actingAs($this->operator)
            ->post(route('kua-themes.move', $second), ['direction' => 'up'])
            ->assertRedirect(route('kua-themes.index'));

        $this->assertSame(50, $second->refresh()->sort_order);
        $this->assertSame(51, $first->refresh()->sort_order);
    }
}
