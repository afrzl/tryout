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
            'deskripsi' => fake()->paragraph(4),
            'peraturan' => fake()->paragraph(4),
            'jenis_ujian' => 'skd',
            'lama_pengerjaan' => 100,
            'waktu_mulai' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'waktu_akhir' => fake()->dateTimeBetween('+1 week', '+3 week'),
            'jumlah_soal' => 110,
            'isPublished' => false,
            'tipe_ujian' => 2,
            'tampil_kunci' => 1,
            'tampil_nilai' => 1,
            'tampil_poin' => 1,
            'random' => 1,
        ];
    }
}
