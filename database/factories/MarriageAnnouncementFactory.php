<?php

namespace Database\Factories;

use App\Models\MarriageAnnouncement;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarriageAnnouncementFactory extends Factory
{
    protected $model = MarriageAnnouncement::class;

    public function definition(): array
    {
        $pria = $this->faker->firstName('male');
        $wanita = $this->faker->firstName('female');

        return [
            'no_pendaftaran' => date('Y').'/'.$this->faker->unique()->numberBetween(1, 999).'/PKN',
            'nama_pria' => $pria,
            'bin_pria' => $this->faker->firstName('male'),
            'asal_pria' => 'Putra dari Bpk. '.$this->faker->name('male').' & Ibu '.$this->faker->name('female'),
            'alamat_pria' => 'Dusun '.$this->faker->word().', Kec. Ampelgading',
            'nama_wanita' => $wanita,
            'binti_wanita' => $this->faker->firstName('male'),
            'asal_wanita' => 'Putri dari Bpk. '.$this->faker->name('male').' & Ibu '.$this->faker->name('female'),
            'alamat_wanita' => 'Dusun '.$this->faker->word().', Kec. Ampelgading',
            'tanggal_akad' => now()->addDays(7)->toDateString(),
            'tempat_nikah' => 'Masjid Nurul Iman',
            'status_wali' => 'Ayah Kandung',
            'active' => true,
        ];
    }
}
