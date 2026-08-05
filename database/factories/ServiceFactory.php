<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'url' => '/permohonan',
            'icon' => fake()->randomElement(['document', 'envelope', 'calendar', 'user']),
            'sort_order' => 0,
            'active' => true,
        ];
    }
}
