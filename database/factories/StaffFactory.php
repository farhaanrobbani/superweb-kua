<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nip' => fake()->numerify('################'),
            'kontak' => fake()->numerify('08##########'),
            'jabatan' => 'Staf',
            'pangkat_golongan' => fake()->randomElement(['Penata Muda', 'Penata', 'Penata Tingkat I', null]),
            'bagian' => fake()->randomElement(['Pimpinan', 'Tata Usaha', 'Jabatan Fungsional', 'Tenaga Non PNS', null]),
            'foto' => null,
            'sort_order' => 0,
            'active' => true,
        ];
    }
}
