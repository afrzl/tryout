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
        Schema::create('ujian_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ujian_id');
            $table->uuid('user_id');
            $table->tinyInteger('status')->default(0)->comment('0: blm mengerjakan, 1: sedang mengerjakan, 2: selesai');
            $table->smallInteger('jml_benar')->default(0);
            $table->smallInteger('jml_salah')->default(0);
            $table->smallInteger('jml_kosong')->default(0);
            $table->smallInteger('nilai')->default(0);
            $table->smallInteger('nilai_twk')->nullable();
            $table->smallInteger('nilai_tiu')->nullable();
            $table->smallInteger('nilai_tkp')->nullable();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_akhir')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_user');
    }
};
