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
    }
}
