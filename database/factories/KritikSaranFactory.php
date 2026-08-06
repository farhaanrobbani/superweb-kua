<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KritikSaran>
 */
class KritikSaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'kontak' => fake()->safeEmail(),
            'kategori' => fake()->randomElement(\App\Models\KritikSaran::KATEGORI),
            'isi' => fake()->paragraph(2),
        ];
    }
}
