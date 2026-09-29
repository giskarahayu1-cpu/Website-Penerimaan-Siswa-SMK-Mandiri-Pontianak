<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_daftar';

    protected $fillable = [
        'id_siswa',
        'tanggal_daftar',
        'status',
        'keterangan',
    ];

    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'id_daftar', 'id_daftar');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_daftar', 'id_daftar');
    }
}
