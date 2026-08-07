<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::firstOrCreate(
            ['url' => '/permohonan'],
            [
                'name' => 'Pengajuan Surat Online',
                'description' => 'Ajukan surat keterangan dan surat pengantar secara online tanpa antre.',
                'icon' => 'document',
                'sort_order' => 1,
                'active' => true,
            ]
        );

        Service::firstOrCreate(
            ['url' => '/pernikahan'],
            [
                'name' => 'Pernikahan',
                'description' => 'Informasi persyaratan, alur, dan SOP layanan pernikahan di KUA.',
                'icon' => 'heart',
                'sort_order' => 2,
                'active' => true,
            ]
        );

        Service::firstOrCreate(
            ['url' => '/cari-akta'],
            [
                'name' => 'Cari Akta Nikah',
                'description' => 'Telusuri data akta nikah secara daring.',
                'icon' => 'document',
                'sort_order' => 3,
                'active' => true,
            ]
        );

        Service::firstOrCreate(
            ['url' => null],
            [
                'name' => 'Konsultasi Keluarga',
                'description' => 'Layanan konsultasi pernikahan dan keluarga di KUA.',
                'slug' => 'konsultasi-keluarga',
                'content' => '<p>KUA menyediakan layanan konsultasi keluarga bagi masyarakat yang membutuhkan pendampingan seputar kehidupan rumah tangga.</p><h3>Materi Konsultasi</h3><ul><li>Persiapan pernikahan</li><li>Keharmonisan keluarga</li><li>Pembinaan keluarga sakinah</li></ul><p>Konsultasi dapat dilakukan di kantor KUA pada jam layanan.</p>',
                'icon' => 'phone',
                'sort_order' => 4,
                'active' => true,
            ]
        );
    }
}
