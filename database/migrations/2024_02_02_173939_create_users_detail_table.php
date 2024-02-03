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
        Schema::create('users_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_hp', 15);
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('sumber_informasi')->nullable();
            $table->tinyInteger('prodi')->comment('1: D3, 2: D4 ST, 3: D4 KS')->nullable();
            $table->string('penempatan')->nullable();
            $table->string('instagram')->nullable();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_detail');
    }
};
