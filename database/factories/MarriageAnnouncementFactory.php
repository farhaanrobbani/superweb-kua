<?php

namespace Database\Factories;

use App\Models\MarriageAnnouncement;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarriageAnnouncementFactory extends Factory
{
    protected $model = MarriageAnnouncement::class;

    public function definition(): array
    {
        return [
            'nama_pria' => $this->faker->name('male'),
            'asal_pria' => 'Putra dari Bpk. '.$this->faker->name('male').' & Ibu '.$this->faker->name('female'),
            'nama_wanita' => $this->faker->name('female'),
            'asal_wanita' => 'Putri dari Bpk. '.$this->faker->name('male').' & Ibu '.$this->faker->name('female'),
            'tanggal_akad' => now()->addDays(7)->toDateString(),
            'tempat_nikah' => 'Masjid Nurul Iman',
            'active' => true,
        ];
    }
}
