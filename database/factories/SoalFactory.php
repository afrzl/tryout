<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Soal>
 */
class SoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ujian_id' => '392b8690-7c0e-4c99-8fb8-ffe2218f2680',
            'soal' => fake()->paragraph(3),
            'id_kunci_jawaban' => '',
        ];
    }
}
