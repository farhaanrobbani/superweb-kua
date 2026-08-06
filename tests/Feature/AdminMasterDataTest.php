<?php

namespace Tests\Feature;

use App\Models\LetterTemplate;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_staff_can_view_letter_types_list(): void
    {
        LetterType::factory()->create(['code' => 'SKU', 'name' => 'Surat Keterangan']);

        $this->actingAs($this->user)
            ->get(route('letter-types.index'))
            ->assertOk()
            ->assertSee('Surat Keterangan');
    }

    public function test_staff_can_create_letter_type(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKX',
                'name' => 'Surat Keterangan X',
                'description' => 'Tes',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
                'active' => 1,
                'publik' => 1,
            ]);

        $response->assertRedirect(route('letter-types.index'));
        $this->assertDatabaseHas('letter_types', ['code' => 'SKX', 'publik' => true]);
    }

    public function test_staff_can_view_templates_list(): void
    {
        $type = LetterType::factory()->create();
        LetterTemplate::factory()->create(['letter_type_id' => $type->id, 'name' => 'Template Utama']);

        $this->actingAs($this->user)
            ->get(route('letter-templates.index'))
            ->assertOk()
            ->assertSee('Template Utama');
    }

    public function test_staff_can_view_kua_settings_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('kua-settings.edit'))
            ->assertOk()
            ->assertSee('Pengaturan KUA');
    }

    public function test_staff_can_update_kua_settings(): void
    {
        $this->actingAs($this->user)
            ->put(route('kua-settings.update'), [
                'instansi' => 'KUA Contoh',
                'alamat' => 'Jl. Contoh No. 1',
                'telepon' => '021000',
                'email' => 'kua@contoh.id',
                'kecamatan' => 'Contoh',
                'kabupaten' => 'Contoh',
                'kode_pos' => '12345',
                'kepala_nama' => 'H. Contoh',
                'kepala_nip' => '123456',
                'kepala_pangkat' => 'Pembina',
                'sk_kepala' => 'SK/001',
                'ttd_path' => '',
            ])
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertDatabaseHas('kua_settings', ['key' => 'instansi', 'value' => 'KUA Contoh']);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('letter-types.index'))->assertRedirect(route('login'));
    }
}
