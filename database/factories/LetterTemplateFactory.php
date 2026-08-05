<?php

namespace Database\Factories;

use App\Models\LetterTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LetterTemplate>
 */
class LetterTemplateFactory extends Factory
{
    protected $model = LetterTemplate::class;

    public function definition(): array
    {
        return [
            'letter_type_id' => \App\Models\LetterType::factory(),
            'name' => fake()->sentence(3),
            'body' => "Yang bertanda tangan di bawah ini menerangkan bahwa [nama] adalah benar warga kami.",
            'active' => true,
        ];
    }
}
