<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaketUjian>
 */
class PaketUjianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(3),
            'harga' => 10000,
            'waktu_mulai' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'waktu_akhir' => fake()->dateTimeBetween('+1 week', '+3 week'),
        ];
    }
}
