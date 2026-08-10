<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarriageService>
 */
class MarriageServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fn (array $attrs) => str()->slug($attrs['name']),
            'description' => fake()->sentence(),
            'persyaratan' => "Syarat satu\nSyarat dua\nSyarat tiga",
            'alur' => "Langkah satu\nLangkah dua\nLangkah tiga",
            'sop' => "Prosedur satu\nProsedur dua",
            'icon' => 'heart',
            'sort_order' => 0,
            'active' => true,
        ];
    }
}
