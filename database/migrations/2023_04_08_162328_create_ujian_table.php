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
        Schema::create('ujian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->integer('harga')->unsigned();
            $table->time('waktu_pengerjaan');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_akhir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
