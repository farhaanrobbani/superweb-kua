<?php

namespace Database\Factories;

use App\Models\LetterType;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Submission>
 */
class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        return [
            'letter_type_id' => LetterType::factory(),
            'nama_pemohon' => fake()->name(),
            'kontak' => fake()->phoneNumber(),
            'data' => ['nama' => fake()->name()],
            'status' => Submission::STATUS_BARU,
            'catatan' => null,
            'token' => Str::random(40),
        ];
    }
}
