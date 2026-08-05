<?php

namespace Database\Factories;

use App\Models\LetterType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LetterType>
 */
class LetterTypeFactory extends Factory
{
    protected $model = LetterType::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->lexify('???')),
            'name' => fake()->words(3, true),
            'description' => null,
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ],
            'active' => true,
        ];
    }
}
