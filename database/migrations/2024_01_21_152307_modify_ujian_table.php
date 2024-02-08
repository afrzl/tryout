<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama');
            $table->text('peraturan')->nullable()->after('deskripsi');
            $table->tinyInteger('tipe_ujian')->after('isPublished')->default(1)->comment('1. satu waktu, 2. periodik');
            $table->tinyInteger('tampil_kunci')->after('tipe_ujian')->default(0)->comment('0. tidak, 1. ya, 2. ya, setelah ditutup');
            $table->tinyInteger('tampil_nilai')->after('tampil_kunci')->default(0)->comment('0. tidak, 1. ya, 2. ya, setelah ditutup');
            $table->tinyInteger('tampil_poin')->after('tampil_nilai')->default(0)->comment('0. tidak, 1. ya');
            $table->tinyInteger('random')->after('tampil_poin')->default(0)->comment('0. tidak, 1. ya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'peraturan', 'tipe_ujian', 'tampil_kunci', 'tampil_nilai', 'tampil_poin', 'random']);
        });
    }
};
