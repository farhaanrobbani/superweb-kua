<?php

namespace Tests\Feature;

use App\Models\KuaSetting;
use App\Models\StaffActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffExportTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    private User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = User::factory()->create([
            'role' => User::ROLE_STAFF,
            'name' => 'Budi Santoso',
            'nip' => '198501012010011001',
            'jabatan' => 'Juru Muda',
            'pangkat' => 'Penata Muda, III/a',
            'ruang_golongan' => 'III/a',
            'grade_tukin' => 9,
            'jumlah_tukin_kotor' => 2500000,
            'jumlah_uang_makan_harian' => 35150,
            'instansi' => 'KUA Ampelgading',
        ]);

        $this->operator = User::factory()->create([
            'role' => User::ROLE_OPERATOR,
            'name' => 'Op KUA',
        ]);

        KuaSetting::set('kecamatan', 'Ampelgading');
        KuaSetting::set('kepala_nama', 'H. Kepala KUA');
        KuaSetting::set('kepala_nip', '197001011990011001');
        KuaSetting::set('kepala_pangkat', 'Pembina, IV/a');

        StaffActivity::create([
            'user_id' => $this->staff->id,
            'tanggal' => '2026-08-04',
            'kegiatan' => 'Pelayanan Pendaftaran Nikah',
            'pekerjaan' => 'Memeriksa berkas permohonan',
            'activity_type_key' => 'pendaftaran_nikah_kantor',
            'total_jumlah' => 3,
        ]);

        StaffActivity::create([
            'user_id' => $this->staff->id,
            'tanggal' => '2026-08-17',
            'kegiatan' => 'Hari Libur / Libur Nasional',
            'pekerjaan' => '-',
            'activity_type_key' => 'libur',
            'total_jumlah' => 0,
        ]);
    }

    public function test_staff_can_download_laporan_kinerja_pdf(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.laporan', ['bulan' => 8, 'tahun' => 2026]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('%PDF', $content);
    }

    public function test_staff_export_ignores_other_users_id(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.laporan', [
                'bulan' => 8,
                'tahun' => 2026,
                'user_id' => $this->operator->id,
            ]));

        $response->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=Laporan_Kinerja_Budi_Santoso_Agustus_2026.pdf');
    }

    public function test_operator_must_select_staff_before_export(): void
    {
        $this->actingAs($this->operator)
            ->get(route('kegiatan.export.laporan', ['bulan' => 8, 'tahun' => 2026]))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_operator_can_export_staff_laporan_kinerja(): void
    {
        $response = $this->actingAs($this->operator)
            ->get(route('kegiatan.export.laporan', [
                'bulan' => 8,
                'tahun' => 2026,
                'user_id' => $this->staff->id,
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_staff_can_download_rekap_pdf_with_attendance(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.rekap', [
                'bulan' => 8,
                'tahun' => 2026,
                'total_hari_kerja' => 20,
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename=Rekap_Laporan_Kinerja_Budi_Santoso_Agustus_2026.pdf');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('%PDF', $content);
    }

    public function test_staff_can_download_rekap_pdf_with_custom_signature_date(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.rekap', [
                'bulan' => 8,
                'tahun' => 2026,
                'total_hari_kerja' => 22,
                'tanggal_ttd' => '20 Agustus 2026',
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('%PDF', $content);
    }

    public function test_staff_can_view_export_page(): void
    {
        $this->actingAs($this->staff)
            ->get(route('kegiatan.export.index', ['bulan' => 8, 'tahun' => 2026]))
            ->assertOk()
            ->assertSee('Export Laporan Kinerja')
            ->assertSee('Export Rekap')
            ->assertSee('export-laporan')
            ->assertSee('export-rekap');
    }

    public function test_operator_export_page_requires_staff_selection(): void
    {
        $this->actingAs($this->operator)
            ->get(route('kegiatan.export.index', ['bulan' => 8, 'tahun' => 2026]))
            ->assertOk()
            ->assertSee('Pilih pegawai (wajib)');
    }

    public function test_staff_can_download_laporan_kinerja_word(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.laporan', [
                'bulan' => 8,
                'tahun' => 2026,
                'format' => 'word',
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->assertHeader('content-disposition', 'attachment; filename="Laporan_Kinerja_Budi_Santoso_Agustus_2026.docx"; filename*=UTF-8\'\'Laporan_Kinerja_Budi_Santoso_Agustus_2026.docx');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringStartsWith('PK', $content);
    }

    public function test_staff_can_download_rekap_word(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kegiatan.export.rekap', [
                'bulan' => 8,
                'tahun' => 2026,
                'total_hari_kerja' => 22,
                'format' => 'word',
            ]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->assertHeader('content-disposition', 'attachment; filename="Rekap_Laporan_Kinerja_Budi_Santoso_Agustus_2026.docx"; filename*=UTF-8\'\'Rekap_Laporan_Kinerja_Budi_Santoso_Agustus_2026.docx');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringStartsWith('PK', $content);
    }

    public function test_operator_can_export_rekap_for_staff(): void
    {
        $this->actingAs($this->operator)
            ->get(route('kegiatan.export.rekap', [
                'bulan' => 8,
                'tahun' => 2026,
                'user_id' => $this->staff->id,
                'total_hari_kerja' => 22,
            ]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_laporan_kinerja_word_uses_custom_print_date(): void
    {
        $xml = $this->laporanWordDocument([
            'format' => 'word',
            'tanggal_ttd' => '20 Agustus 2026',
        ]);

        $this->assertStringContainsString('20 Agustus 2026', $xml);
        $this->assertStringNotContainsString('31 Agustus 2026', $xml);
    }

    public function test_laporan_kinerja_word_defaults_print_date_to_end_of_month(): void
    {
        $xml = $this->laporanWordDocument(['format' => 'word']);

        $this->assertStringContainsString('31 Agustus 2026', $xml);
    }

    public function test_export_page_shows_custom_print_date_field(): void
    {
        $this->actingAs($this->staff)
            ->get(route('kegiatan.export.index', ['bulan' => 8, 'tahun' => 2026]))
            ->assertOk()
            ->assertSee('Tanggal Dicetak (opsional)');
    }

    private function laporanWordDocument(array $extra): string
    {
        $response = $this->actingAs($this->staff)->get(route('kegiatan.export.laporan', array_merge([
            'bulan' => 8,
            'tahun' => 2026,
        ], $extra)));

        $response->assertOk();

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $tmp = tempnam(sys_get_temp_dir(), 'lapkin');
        file_put_contents($tmp, $content);

        $zip = new \ZipArchive;
        $zip->open($tmp);
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        unlink($tmp);

        $this->assertIsString($xml);

        return $xml;
    }
}

