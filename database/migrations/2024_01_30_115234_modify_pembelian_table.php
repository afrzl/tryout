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
        Schema::table('pembelian', function (Blueprint $table) {
            // $table->dropColumn('status_pengerjaan', 'waktu_mulai_pengerjaan', 'waktu_selesai_pengerjaan');
            $table->bigInteger('id_voucher')->nullable()->unsigned()->after('jenis_pembayaran');

            $table->foreign('id_voucher')->references('id')->on('voucher')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn('id_voucher');
        });
    }
};
