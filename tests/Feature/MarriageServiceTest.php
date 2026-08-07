<?php

namespace Tests\Feature;

use App\Models\MarriageService;
use App\Models\User;
use Database\Seeders\MarriageServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarriageServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => User::ROLE_STAFF]);
    }

    public function test_staff_can_create_marriage_topic(): void
    {
        $this->actingAs($this->user)
            ->post(route('marriage-services.store'), [
                'name' => 'Pendaftaran Nikah',
                'description' => 'Deskripsi layanan.',
                'persyaratan' => "Syarat satu\nSyarat dua",
                'alur' => "Langkah satu\nLangkah dua",
                'sop' => "Prosedur satu",
                'target_url' => '/permohonan?jenis=SPD',
                'icon' => 'heart',
                'sort_order' => 1,
                'active' => 1,
            ])
            ->assertRedirect(route('marriage-services.index'));

        $this->assertDatabaseHas('marriage_services', [
            'name' => 'Pendaftaran Nikah',
            'slug' => 'pendaftaran-nikah',
            'target_url' => '/permohonan?jenis=SPD',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('marriage-services.store'), [
                'name' => '',
                'active' => 1,
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_staff_can_update_marriage_topic(): void
    {
        $service = MarriageService::factory()->create(['name' => 'Lama', 'slug' => 'lama']);

        $this->actingAs($this->user)
            ->put(route('marriage-services.update', $service), [
                'name' => 'Baru',
                'persyaratan' => 'Syarat baru',
                'alur' => "Alur satu\nAlur dua",
                'sop' => 'SOP baru',
                'target_url' => '/cari-akta',
                'icon' => 'check',
                'active' => 1,
            ])
            ->assertRedirect(route('marriage-services.index'));

        $this->assertDatabaseHas('marriage_services', [
            'id' => $service->id,
            'name' => 'Baru',
            'slug' => 'baru',
            'target_url' => '/cari-akta',
        ]);
    }

    public function test_staff_can_delete_marriage_topic(): void
    {
        $service = MarriageService::factory()->create();

        $this->actingAs($this->user)
            ->delete(route('marriage-services.destroy', $service))
            ->assertRedirect(route('marriage-services.index'));

        $this->assertDatabaseMissing('marriage_services', ['id' => $service->id]);
    }

    public function test_public_index_shows_only_active_topics_in_order(): void
    {
        MarriageService::factory()->create(['name' => 'Aktif 1', 'sort_order' => 2, 'active' => true]);
        MarriageService::factory()->create(['name' => 'Aktif 2', 'sort_order' => 1, 'active' => true]);
        MarriageService::factory()->create(['name' => 'Nonaktif', 'sort_order' => 0, 'active' => false]);

        $response = $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Aktif 1')
            ->assertSee('Aktif 2')
            ->assertDontSee('Nonaktif');

        $this->assertStringBefore($response->getContent(), 'Aktif 2', 'Aktif 1');
    }

    public function test_public_index_renders_sections_and_button(): void
    {
        $service = MarriageService::factory()->create([
            'name' => 'Duplikat Akta Nikah',
            'persyaratan' => "KTP\nKK",
            'alur' => "Ajukan online\nDatang ke KUA",
            'sop' => "Periksa berkas\nTerbitkan duplikat",
            'target_url' => '/permohonan?jenis=SPD',
            'active' => true,
        ]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Persyaratan')
            ->assertSee('KTP')
            ->assertSee('Alur')
            ->assertSee('Ajukan online')
            ->assertSee('SOP')
            ->assertSee('Periksa berkas')
            ->assertSee('Ajukan Permohonan')
            ->assertSee('permohonan?jenis=SPD', false);
    }

    public function test_public_index_shows_no_button_when_no_target_url(): void
    {
        MarriageService::factory()->create([
            'name' => 'Legalisir',
            'persyaratan' => 'Buku nikah',
            'alur' => 'Datang ke KUA',
            'sop' => null,
            'target_url' => null,
            'active' => true,
        ]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Legalisir')
            ->assertDontSee('Ajukan Permohonan')
            ->assertDontSee('diurus langsung di kantor KUA');
    }

    public function test_public_index_uses_custom_section_labels_with_default_fallback(): void
    {
        MarriageService::factory()->create([
            'name' => 'Duplikat Akta Nikah',
            'persyaratan' => "KTP\nKK",
            'alur' => "Ajukan online\nDatang ke KUA",
            'sop' => "Periksa berkas\nTerbitkan duplikat",
            'persyaratan_label' => 'Berkas yang Dibawa',
            'alur_label' => 'Tahapan',
            'sop_label' => null,
            'active' => true,
        ]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Berkas yang Dibawa')
            ->assertSee('Tahapan')
            ->assertSee('SOP')
            ->assertDontSee('Persyaratan');
    }

    public function test_staff_can_set_custom_section_labels(): void
    {
        $this->actingAs($this->user)
            ->post(route('marriage-services.store'), [
                'name' => 'Topik Baru',
                'persyaratan' => "Satu\nDua",
                'alur' => "Langkah satu\nLangkah dua",
                'sop' => "Prosedur satu",
                'persyaratan_label' => 'Berkas',
                'alur_label' => 'Langkah',
                'sop_label' => 'Prosedur',
                'active' => 1,
            ])
            ->assertRedirect(route('marriage-services.index'));

        $this->assertDatabaseHas('marriage_services', [
            'name' => 'Topik Baru',
            'persyaratan_label' => 'Berkas',
            'alur_label' => 'Langkah',
            'sop_label' => 'Prosedur',
        ]);
    }

    public function test_public_pernikahan_header_keeps_layanan_services(): void
    {
        \App\Models\Service::factory()->create(['name' => 'Layanan Asli', 'url' => '/permohonan', 'active' => true]);
        MarriageService::factory()->create(['name' => 'Topik Nikah', 'active' => true]);

        $this->get(route('pernikahan.index'))
            ->assertOk()
            ->assertSee('Layanan Asli')
            ->assertSee('Topik Nikah');
    }

    public function test_seeder_creates_ten_topics_idempotently(): void
    {
        $this->seed(MarriageServiceSeeder::class);
        $this->seed(MarriageServiceSeeder::class);

        $this->assertSame(10, MarriageService::count());
        $this->assertDatabaseHas('marriage_services', ['slug' => 'pendaftaran-nikah']);
        $this->assertDatabaseHas('marriage_services', ['slug' => 'cari-akta', 'target_url' => '/cari-akta']);
        $this->assertDatabaseHas('marriage_services', ['slug' => 'duplikat-akta-nikah', 'target_url' => '/permohonan?jenis=SPD']);
        $this->assertDatabaseHas('marriage_services', ['slug' => 'legalisir', 'target_url' => null]);
    }

    private function assertStringBefore(string $haystack, string $needle, string $otherNeedle): void
    {
        $this->assertLessThan(
            mb_strpos($haystack, $otherNeedle),
            mb_strpos($haystack, $needle)
        );
    }
}
