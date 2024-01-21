<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PaketUjianSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RolesAndPermissionsSeeder::class,
            PaketUjianSeeder::class,
        ]);
        // \App\Models\Ujian::factory(5)->create();
        // \App\Models\Soal::factory(45)->create();
        // for ($i=162; $i < 207; $i++) {
        //     for ($j=0; $j < 5; $j++) {
        //         \App\Models\Jawaban::factory(1)->create([
        //             'soal_id' => $i,
        //             'point' => $j == 0 ? 5 : 0,
        //         ]);
        //     }
        // }
        // for ($i=1; $i < 16; $i++) {
        //     \App\Models\Jawaban::factory(5)->create([
        //         'soal_id' => $i + 16,
        //     ]);
        // }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
