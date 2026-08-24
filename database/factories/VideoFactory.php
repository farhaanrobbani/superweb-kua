<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5),
            'excerpt' => $this->faker->sentence(12),
            'content' => '<p>'.$this->faker->paragraph(3).'</p>',
            'video_url' => 'https://www.youtube.com/watch?v='.$this->faker->regexify('[A-Za-z0-9_-]{11}'),
            'published_at' => now(),
            'active' => true,
        ];
    }
}
