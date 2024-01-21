<?php

namespace Database\Seeders;

use App\Models\PaketUjian;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaketUjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketUjian::factory(5)->create();
    }
}
