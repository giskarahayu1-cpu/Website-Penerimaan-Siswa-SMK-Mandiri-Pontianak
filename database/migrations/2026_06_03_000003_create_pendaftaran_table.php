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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_daftar');
            $table->unsignedBigInteger('id_siswa');
            $table->date('tanggal_daftar');
            $table->enum('status', ['menunggu', 'verifikasi', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('calon_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
