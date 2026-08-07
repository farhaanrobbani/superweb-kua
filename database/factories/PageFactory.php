<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(),
            'content' => null,
            'active' => true,
        ];
    }
}
