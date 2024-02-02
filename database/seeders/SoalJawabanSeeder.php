<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoalJawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //non tkp
        // $soal = \App\Models\Soal::factory(35)->create(['jenis_soal' => 'tiu']);
        // foreach ($soal as $key => $value) {
        //     $jawaban = \App\Models\Jawaban::factory(5)->create([
        //                 'soal_id' => $value->id,
        //                 'point' => 0,
        //             ]);

        //     foreach ($jawaban as $key2 => $value2) {
        //         if ($key2 == 1) {
        //             $updateKunci = \App\Models\Soal::findOrFail($value->id)
        //                         ->update(['kunci_jawaban' => $value2->id]);
        //         }
        //     }
        // }

        //tkp
        $soal = \App\Models\Soal::factory(45)->create(['jenis_soal' => 'tkp']);
        foreach ($soal as $key => $value) {
            for ($i=1; $i <= 5; $i++) {
                $jawaban = \App\Models\Jawaban::factory(1)->create([
                            'soal_id' => $value->id,
                            'point' => $i,
                        ]);
            }
        }
    }
}
