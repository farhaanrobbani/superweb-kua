<?php

namespace Database\Factories;

use App\Enums\AnnouncementCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'content' => fake()->paragraph(3),
            'category' => fake()->randomElement(AnnouncementCategory::cases()),
            'published_at' => now(),
            'active' => true,
        ];
    }
}
