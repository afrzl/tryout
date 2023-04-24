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
        Schema::table('jawaban_peserta', function (Blueprint $table) {
            $table->boolean('ragu_ragu')->nullable()->default(false)->after('jawaban_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_peserta', function (Blueprint $table) {
            $table->dropColumn('ragu_ragu');
        });
    }
};
