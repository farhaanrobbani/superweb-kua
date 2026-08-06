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

    public function test_letter_type_created_without_checkboxes_is_inactive_and_not_public(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKY',
                'name' => 'Surat Keterangan Y',
                'description' => 'Tanpa centang aktif/tampil',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertDatabaseHas('letter_types', ['code' => 'SKY', 'active' => false, 'publik' => false]);
    }

    public function test_letter_type_field_order_is_preserved(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKZ',
                'name' => 'Surat Keterangan Z',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                    ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => 1],
                    ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => 0],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertSame(
            ['nama', 'nik', 'tanggal_lahir'],
            array_column(LetterType::where('code', 'SKZ')->firstOrFail()->fields, 'name')
        );
    }

    public function test_letter_type_select_options_are_split_by_comma(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKO',
                'name' => 'Surat Keterangan Opsi',
                'fields' => [
                    ['name' => 'jenis', 'label' => 'Jenis', 'type' => 'select', 'required' => 1, 'options' => ['Pria, Wanita']],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $fields = LetterType::where('code', 'SKO')->firstOrFail()->fields;
        $this->assertSame(['Pria', 'Wanita'], $fields[0]['options']);
    }

    public function test_letter_type_select_options_trim_and_dedupe(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKP',
                'name' => 'Surat Keterangan Opsi Bersih',
                'fields' => [
                    ['name' => 'alasan', 'label' => 'Alasan', 'type' => 'select', 'required' => 1, 'options' => ['Ekonomi,  Ekonomi , Perselisihan', '']],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $fields = LetterType::where('code', 'SKP')->firstOrFail()->fields;
        $this->assertSame(['Ekonomi', 'Perselisihan'], $fields[0]['options']);
    }

    public function test_letter_type_non_select_field_options_are_cleared(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKQ2',
                'name' => 'Surat Keterangan Tanpa Opsi',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1, 'options' => ['']],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $fields = LetterType::where('code', 'SKQ2')->firstOrFail()->fields;
        $this->assertArrayNotHasKey('options', $fields[0]);
    }

    public function test_staff_can_save_permohonan_body_on_letter_type(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKQ',
                'name' => 'Surat Keterangan Q',
                'description' => 'Tes narasi',
                'permohonan_body' => "Memohon agar diterbitkan Surat atas nama [nama].\n\nDemikian Surat Permohonan ini kami buat.",
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertDatabaseHas('letter_types', ['code' => 'SKQ']);

        $type = LetterType::where('code', 'SKQ')->firstOrFail();
        $this->assertStringContainsString('[nama]', $type->permohonan_body);

        $this->actingAs($this->user)
            ->put(route('letter-types.update', $type), [
                'code' => 'SKQ',
                'name' => 'Surat Keterangan Q',
                'description' => 'Tes narasi',
                'permohonan_body' => 'Narasi diperbarui.',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertDatabaseHas('letter_types', ['id' => $type->id, 'permohonan_body' => '<p>Narasi diperbarui.</p>']);
    }

    public function test_staff_can_save_permohonan_fields_on_letter_type(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SQP',
                'name' => 'Surat Keterangan QP',
                'description' => 'Tes field permohonan',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                    ['name' => 'nik', 'label' => 'NIK', 'type' => 'text', 'required' => 1],
                ],
                'permohonan_fields' => ['nama'],
            ])
            ->assertRedirect(route('letter-types.index'));

        $type = LetterType::where('code', 'SQP')->firstOrFail();
        $this->assertSame(['nama'], $type->permohonan_fields);
    }

    public function test_letter_type_form_shows_permohonan_fields_checkboxes(): void
    {
        $type = LetterType::factory()->create(['fields' => [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
        ]]);

        $this->actingAs($this->user)
            ->get(route('letter-types.create'))
            ->assertOk()
            ->assertSee('Field yang Tampil di Surat Permohonan');

        $this->actingAs($this->user)
            ->get(route('letter-types.edit', $type))
            ->assertOk()
            ->assertSee('Field yang Tampil di Surat Permohonan');
    }

    public function test_letter_type_edit_form_shows_permohonan_body_textarea(): void
    {
        $type = LetterType::factory()->create(['permohonan_body' => 'Narasi contoh.']);

        $this->actingAs($this->user)
            ->get(route('letter-types.edit', $type))
            ->assertOk()
            ->assertSee('Narasi Surat Permohonan')
            ->assertSee('Narasi contoh.');
    }

    public function test_letter_type_update_without_checkboxes_turns_off_active_and_publik(): void
    {
        $type = LetterType::factory()->create(['active' => true, 'publik' => true]);

        $this->actingAs($this->user)
            ->put(route('letter-types.update', $type), [
                'code' => $type->code,
                'name' => $type->name,
                'description' => $type->description,
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertDatabaseHas('letter_types', ['id' => $type->id, 'active' => false, 'publik' => false]);
    }

    public function test_letter_template_created_without_checkbox_is_inactive(): void
    {
        $type = LetterType::factory()->create(['active' => true]);

        $this->actingAs($this->user)
            ->post(route('letter-templates.store'), [
                'letter_type_id' => $type->id,
                'name' => 'Template Tanpa Centang',
                'body' => 'Isi template tanpa centang aktif.',
            ])
            ->assertRedirect(route('letter-templates.index'));

        $this->assertDatabaseHas('letter_templates', [
            'name' => 'Template Tanpa Centang',
            'active' => false,
        ]);
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

    public function test_letter_template_body_is_sanitized(): void
    {
        $type = LetterType::factory()->create(['active' => true]);

        $this->actingAs($this->user)
            ->post(route('letter-templates.store'), [
                'letter_type_id' => $type->id,
                'name' => 'Template Sanitasi',
                'body' => '<script></script><p>Halo <strong>dunia</strong> [nama].</p>',
            ])
            ->assertRedirect(route('letter-templates.index'));

        $this->assertDatabaseHas('letter_templates', [
            'name' => 'Template Sanitasi',
            'body' => '<p>Halo <strong>dunia</strong> [nama].</p>',
        ]);
    }

    public function test_letter_type_permohonan_body_is_sanitized(): void
    {
        $this->actingAs($this->user)
            ->post(route('letter-types.store'), [
                'code' => 'SKS',
                'name' => 'Surat Keterangan S',
                'permohonan_body' => '<script></script><p>Memohon surat atas nama [nama].</p>',
                'fields' => [
                    ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => 1],
                ],
            ])
            ->assertRedirect(route('letter-types.index'));

        $this->assertDatabaseHas('letter_types', [
            'code' => 'SKS',
            'permohonan_body' => '<p>Memohon surat atas nama [nama].</p>',
        ]);
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
            ])
            ->assertRedirect(route('kua-settings.edit'));

        $this->assertDatabaseHas('kua_settings', ['key' => 'instansi', 'value' => 'KUA Contoh']);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('letter-types.index'))->assertRedirect(route('login'));
    }
}
