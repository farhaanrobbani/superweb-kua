<?php

namespace Database\Factories;

use App\Models\Letter;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Letter>
 */
class LetterFactory extends Factory
{
    protected $model = Letter::class;

    public function definition(): array
    {
        return [
            'letter_type_id' => LetterType::factory(),
            'nomor' => null,
            'tanggal_surat' => null,
            'perihal' => fake()->sentence(3),
            'data' => ['nama' => fake()->name()],
            'status' => Letter::STATUS_DRAFT,
            'created_by' => User::factory(),
            'approved_by' => null,
            'approved_at' => null,
            'keterangan' => null,
        ];
    }
}
