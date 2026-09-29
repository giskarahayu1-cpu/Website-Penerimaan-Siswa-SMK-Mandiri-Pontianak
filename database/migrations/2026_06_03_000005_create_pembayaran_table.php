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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_daftar');
            $table->date('tanggal_bayar');
            $table->integer('jumlah');
            $table->string('bukti_bayar', 255);
            $table->enum('status', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->timestamps();

            $table->foreign('id_daftar')->references('id_daftar')->on('pendaftaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
