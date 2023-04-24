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
        Schema::create('jawaban_peserta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pembelian_id')->unsigned();
            $table->bigInteger('jawaban_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade');
            $table->foreign('jawaban_id')->references('id')->on('jawaban')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_peserta');
    }
};
