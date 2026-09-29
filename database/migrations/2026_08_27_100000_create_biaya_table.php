<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biaya', function (Blueprint $table) {
            $table->id('id_biaya');
            $table->string('deskripsi');
            $table->integer('nominal');
            $table->timestamps();
        });

        // Seed default costs
        DB::table('biaya')->insert([
            ['deskripsi' => 'SPP 2 Bulan @ Rp200.000 (Bulan Juli & Agustus)', 'nominal' => 400000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Sumbangan Pembangunan', 'nominal' => 800000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Seragam Olahraga (1 Stel)', 'nominal' => 300000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Seragam Praktek / Rumpun (1 Stel)', 'nominal' => 300000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'PLS (Pengenalan Lingkungan Sekolah)', 'nominal' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Iuran OSIS (Selama 1 Tahun)', 'nominal' => 450000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Biaya Pemeliharaan & Perbaikan Komputer', 'nominal' => 300000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Biaya Cetak Raport & Kartu Pelajar', 'nominal' => 200000, 'created_at' => now(), 'updated_at' => now()],
            ['deskripsi' => 'Biaya Administrasi', 'nominal' => 300000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya');
    }
};
