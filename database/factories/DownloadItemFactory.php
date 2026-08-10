<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DownloadItem>
 */
class DownloadItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->sentence(),
            'category' => fake()->optional()->word(),
            'file' => 'downloads/contoh.pdf',
            'external_url' => null,
            'active' => true,
            'sort_order' => 0,
        ];
    }

    public function external(): static
    {
        return $this->state(fn () => [
            'file' => null,
            'external_url' => fake()->url(),
        ]);
    }
}
