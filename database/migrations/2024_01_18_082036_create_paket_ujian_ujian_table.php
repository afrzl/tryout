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
        Schema::create('paket_ujian_ujian', function (Blueprint $table) {
            $table->id();
            $table->uuid('paket_ujian_id');
            $table->uuid('ujian_id');

            $table->foreign('paket_ujian_id')
                    ->references('id')
                    ->on('paket_ujian')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->foreign('ujian_id')
                    ->references('id')
                    ->on('ujian')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_ujian_ujian');
    }
};
