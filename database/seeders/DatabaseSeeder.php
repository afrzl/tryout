<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UjianSeeder;
use Database\Seeders\PaketUjianSeeder;
use Database\Seeders\SoalJawabanSeeder;
// use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RolesAndPermissionsSeeder::class,
            // PaketUjianSeeder::class,
            // UjianSeeder::class,
            // SoalJawabanSeeder::class,
            \Database\Seeders\RolesAndPermissionsSeeder::class,
            // \Database\Seeders\PaketUjianSeeder::class,
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
