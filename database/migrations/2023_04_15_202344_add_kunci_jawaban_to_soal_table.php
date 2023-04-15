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
        Schema::table('soal', function (Blueprint $table) {
            $table->bigInteger('id_kunci_jawaban')->unsigned()->nullable()->after('soal');
            $table->foreign('id_kunci_jawaban')->references('id')->on('jawaban')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('id_kunci_jawaban');
        });
    }
};
