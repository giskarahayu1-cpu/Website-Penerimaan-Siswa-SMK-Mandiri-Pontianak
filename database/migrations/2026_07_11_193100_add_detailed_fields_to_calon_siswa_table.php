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
        Schema::table('calon_siswa', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('nama');
            $table->string('no_kk', 20)->nullable()->after('nik');
            $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('agama', 30)->nullable()->after('tanggal_lahir');
            $table->string('kewarganegaraan', 50)->nullable()->after('agama');
            $table->string('kelurahan', 100)->nullable()->after('alamat');
            $table->string('kecamatan', 100)->nullable()->after('kelurahan');
            $table->string('kota', 100)->nullable()->after('kecamatan');
            $table->string('provinsi', 100)->nullable()->after('kota');
            $table->integer('tinggi_badan')->nullable()->after('provinsi');
            $table->string('penyakit', 150)->nullable()->after('tinggi_badan');
            $table->integer('jumlah_saudara')->nullable()->after('penyakit');
            
            // Data Orang Tua / Wali
            $table->string('nama_ayah', 100)->nullable()->after('email');
            $table->string('pekerjaan_ayah', 100)->nullable()->after('nama_ayah');
            $table->string('pendidikan_ayah', 50)->nullable()->after('pekerjaan_ayah');
            $table->string('penghasilan_ayah', 100)->nullable()->after('pendidikan_ayah');
            
            $table->string('nama_ibu', 100)->nullable()->after('penghasilan_ayah');
            $table->string('pekerjaan_ibu', 100)->nullable()->after('nama_ibu');
            $table->string('pendidikan_ibu', 50)->nullable()->after('pekerjaan_ibu');
            $table->string('penghasilan_ibu', 100)->nullable()->after('pendidikan_ibu');
            
            $table->string('no_hp_ortu', 20)->nullable()->after('penghasilan_ibu');
            
            $table->string('nama_wali', 100)->nullable()->after('no_hp_ortu');
            $table->string('pekerjaan_wali', 100)->nullable()->after('nama_wali');
            $table->text('alamat_wali')->nullable()->after('pekerjaan_wali');
            $table->string('no_hp_wali', 20)->nullable()->after('alamat_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_siswa', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'no_kk', 'tempat_lahir', 'tanggal_lahir', 'agama', 'kewarganegaraan',
                'kelurahan', 'kecamatan', 'kota', 'provinsi', 'tinggi_badan', 'penyakit', 'jumlah_saudara',
                'nama_ayah', 'pekerjaan_ayah', 'pendidikan_ayah', 'penghasilan_ayah',
                'nama_ibu', 'pekerjaan_ibu', 'pendidikan_ibu', 'penghasilan_ibu', 'no_hp_ortu',
                'nama_wali', 'pekerjaan_wali', 'alamat_wali', 'no_hp_wali'
            ]);
        });
    }
};
