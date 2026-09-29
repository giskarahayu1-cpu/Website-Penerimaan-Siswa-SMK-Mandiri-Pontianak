<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonSiswa extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($siswa) {
            $freeTextFields = [
                'nama',
                'tempat_lahir',
                'alamat',
                'kewarganegaraan',
                'penyakit',
                'nama_ayah',
                'nama_ibu',
                'nama_wali',
                'alamat_wali',
                'kelas'
            ];

            foreach ($freeTextFields as $field) {
                if (isset($siswa->$field) && is_string($siswa->$field)) {
                    $siswa->$field = strtoupper($siswa->$field);
                }
            }
        });
    }

    protected $table = 'calon_siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'user_id',
        'nisn',
        'nama',
        'nik',
        'no_kk',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kewarganegaraan',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'tinggi_badan',
        'penyakit',
        'jumlah_saudara',
        'anak_ke',
        'no_hp',
        'email',
        'jurusan',
        'kelas',
        'nama_ayah',
        'pekerjaan_ayah',
        'pendidikan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'pendidikan_ibu',
        'penghasilan_ibu',
        'no_hp_ortu',
        'nama_wali',
        'pekerjaan_wali',
        'alamat_wali',
        'no_hp_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class, 'id_siswa', 'id_siswa');
    }
}
