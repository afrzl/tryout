<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(100)->create();
        // \App\Models\Ujian::factory(5)->create();
        for ($i=0; $i < 11; $i++) {
            \App\Models\Soal::factory(1)->create([
                'id_kunci_jawaban' => ($i * 5) + 81,
            ]);
        }
        for ($i=1; $i < 16; $i++) {
            \App\Models\Jawaban::factory(5)->create([
                'soal_id' => $i + 16,
            ]);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
