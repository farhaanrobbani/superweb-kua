<?php

namespace Tests\Feature;

use App\Models\KritikSaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KritikSaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_form_shows_sub_navbar_and_form_without_login(): void
    {
        $this->get(route('kritik-saran.create'))
            ->assertOk()
            ->assertSee('Kritik & Saran', false)
            ->assertSee('Daftar Pegawai')
            ->assertSee('nama')
            ->assertSee('isi');
    }

    public function test_guest_can_submit_kritik_saran(): void
    {
        $this->post(route('kritik-saran.store'), [
            'nama' => 'Budi Santoso',
            'kontak' => 'budi@mail.com',
            'kategori' => 'Pelayanan',
            'isi' => 'Pelayanannya sudah bagus, mohon diperbaiki lagi.',
        ])->assertRedirect();

        $this->assertDatabaseHas('kritik_sarans', [
            'nama' => 'Budi Santoso',
            'kategori' => 'Pelayanan',
        ]);
    }

    public function test_kritik_saran_requires_nama_and_isi(): void
    {
        $this->post(route('kritik-saran.store'), ['nama' => '', 'isi' => ''])
            ->assertSessionHasErrors(['nama', 'isi']);

        $this->assertDatabaseCount('kritik_sarans', 0);
    }

    public function test_guest_cannot_access_admin_kritik_saran(): void
    {
        $this->get(route('kritik-saran.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_list_and_delete_kritik_saran(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_STAFF]);
        $feedback = KritikSaran::factory()->create(['nama' => 'Siti Rahma']);

        $this->actingAs($user)
            ->get(route('kritik-saran.index'))
            ->assertOk()
            ->assertSee('Siti Rahma');

        $this->actingAs($user)
            ->get(route('kritik-saran.show', $feedback))
            ->assertOk()
            ->assertSee($feedback->isi);

        $this->actingAs($user)
            ->delete(route('kritik-saran.destroy', $feedback))
            ->assertRedirect(route('kritik-saran.index'));

        $this->assertDatabaseMissing('kritik_sarans', ['id' => $feedback->id]);
    }

    public function test_landing_shows_sub_navbar_items(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Daftar Pegawai')
            ->assertSee('Kritik & Saran', false);
    }
}
