<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ujian>
 */
class UjianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->sentence(4),
            'harga' => (fake()->randomNumber(7, true) / 100),
            'lama_pengerjaan' => fake()->time(),
            'waktu_mulai' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'waktu_akhir' => fake()->dateTimeBetween('+1 week', '+3 week'),
            'jumlah_soal' => fake()->numberBetween(1, 20),
        ];
    }
}
