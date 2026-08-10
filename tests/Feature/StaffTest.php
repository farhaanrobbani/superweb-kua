<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_OPERATOR]);
    }

    public function test_guest_cannot_access_admin_staff_crud(): void
    {
        $this->get(route('staff.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_and_edit_pegawai(): void
    {
        $this->actingAs($this->user)
            ->post(route('staff.store'), [
                'nama' => 'Ahmad Fauzi',
                'nip' => '198001012010011001',
                'kontak' => '081234567890',
                'jabatan' => 'Penghulu',
                'pangkat_golongan' => 'Penata, III/c',
                'bagian' => 'Jabatan Fungsional',
                'sort_order' => 1,
                'active' => 1,
            ])
            ->assertRedirect(route('staff.index'));

        $staff = Staff::where('nama', 'Ahmad Fauzi')->first();
        $this->assertNotNull($staff);
        $this->assertSame('Penghulu', $staff->jabatan);
        $this->assertSame('081234567890', $staff->kontak);
        $this->assertTrue($staff->active);

        $this->actingAs($this->user)
            ->put(route('staff.update', $staff), [
                'nama' => 'Ahmad Fauzi, S.Ag',
                'nip' => '198001012010011001',
                'kontak' => '082198765432',
                'jabatan' => 'Kepala KUA',
                'pangkat_golongan' => 'Penata, III/c',
                'bagian' => 'Pimpinan',
                'sort_order' => 0,
                'active' => 0,
            ])
            ->assertRedirect(route('staff.index'));

        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'nama' => 'Ahmad Fauzi, S.Ag',
            'kontak' => '082198765432',
            'jabatan' => 'Kepala KUA',
            'active' => 0,
        ]);
    }

    public function test_staff_update_without_checkbox_turns_off_active(): void
    {
        $staff = Staff::factory()->create(['active' => true]);

        $this->actingAs($this->user)
            ->put(route('staff.update', $staff), [
                'nama' => 'Nama Diubah',
                'jabatan' => 'Penghulu',
            ])
            ->assertRedirect(route('staff.index'));

        $this->assertDatabaseHas('staff', ['id' => $staff->id, 'active' => 0]);
    }

    public function test_staff_can_delete_pegawai(): void
    {
        $staff = Staff::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('staff.destroy', $staff))
            ->assertRedirect(route('staff.index'));

        $this->assertDatabaseMissing('staff', ['id' => $staff->id]);
    }

    public function test_staff_requires_nama_and_jabatan(): void
    {
        $this->actingAs($this->user)
            ->post(route('staff.store'), ['nama' => '', 'jabatan' => ''])
            ->assertSessionHasErrors(['nama', 'jabatan']);

        $this->assertDatabaseCount('staff', 0);
    }

    public function test_public_page_shows_navbar_item_and_active_pegawai(): void
    {
        Staff::factory()->create([
            'nama' => 'H. Abdul Malik',
            'jabatan' => 'Kepala KUA',
            'bagian' => 'Pimpinan',
            'kontak' => '081111222333',
            'active' => true,
        ]);
        Staff::factory()->create([
            'nama' => 'Siti Rahma',
            'jabatan' => 'Staf TU',
            'bagian' => 'Tata Usaha',
            'active' => false,
        ]);

        $this->get(route('pegawai.index'))
            ->assertOk()
            ->assertSee('Daftar Pegawai')
            ->assertSee('Struktur Organisasi')
            ->assertSee('Data Pegawai')
            ->assertSee('Kontak')
            ->assertSee('H. Abdul Malik')
            ->assertSee('081111222333')
            ->assertSee('Pimpinan')
            ->assertDontSee('Siti Rahma');
    }

    public function test_public_page_groups_pegawai_without_bagian_under_pegawai(): void
    {
        Staff::factory()->create(['nama' => 'Tanpa Bagian', 'bagian' => null, 'active' => true]);

        $this->get(route('pegawai.index'))
            ->assertOk()
            ->assertSee('Pegawai')
            ->assertSee('Tanpa Bagian');
    }
}
