<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@smkmandiri.com'],
            [
                'name' => 'Admin PPDB SMK Mandiri',
                'password' => \Illuminate\Support\Facades\Hash::make('adminsmkmandiri123'),
                'role' => 'admin',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'kepsek@smkmandiri.com'],
            [
                'name' => 'Kepala Sekolah SMK Mandiri',
                'password' => \Illuminate\Support\Facades\Hash::make('kepsek123'),
                'role' => 'kepsek',
            ]
        );

        $jurusans = ['Akuntansi', 'Animasi', 'Pemasaran'];

        // Scan storage directory and reconstruct previous student data (IDs 6, 7, 8, 9, etc.)
        $berkasPath = storage_path('app/public/uploads/berkas');
        $studentIds = [];
        $berkasFiles = [];

        if (file_exists($berkasPath)) {
            $files = scandir($berkasPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                // Filename format: berkas_nama_berkas_idSiswa_timestamp.ext
                if (strpos($file, 'berkas_') === 0) {
                    $namePart = substr($file, 7);
                    $lastUnderscore = strrpos($namePart, '_');
                    if ($lastUnderscore !== false) {
                        $secondLastUnderscore = strrpos(substr($namePart, 0, $lastUnderscore), '_');
                        if ($secondLastUnderscore !== false) {
                            $type = substr($namePart, 0, $secondLastUnderscore);
                            $dbType = str_replace('_', ' ', $type);
                            $idSiswa = substr($namePart, $secondLastUnderscore + 1, $lastUnderscore - $secondLastUnderscore - 1);
                            
                            if (is_numeric($idSiswa)) {
                                $idSiswa = (int)$idSiswa;
                                $studentIds[$idSiswa] = true;
                                $berkasFiles[] = [
                                    'id_siswa' => $idSiswa,
                                    'nama_berkas' => $dbType,
                                    'file' => 'uploads/berkas/' . $file
                                ];
                            }
                        }
                    }
                }
            }
        }

        $pembayaranPath = storage_path('app/public/uploads/pembayaran');
        $pembayaranFiles = [];

        if (file_exists($pembayaranPath)) {
            $files = scandir($pembayaranPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                // Filename format: bukti_bayar_idSiswa_timestamp.ext
                if (strpos($file, 'bukti_bayar_') === 0) {
                    $namePart = substr($file, 12);
                    $underscorePos = strpos($namePart, '_');
                    if ($underscorePos !== false) {
                        $idSiswa = substr($namePart, 0, $underscorePos);
                        if (is_numeric($idSiswa)) {
                            $idSiswa = (int)$idSiswa;
                            $studentIds[$idSiswa] = true;
                            $pembayaranFiles[] = [
                                'id_siswa' => $idSiswa,
                                'file' => 'uploads/pembayaran/' . $file
                            ];
                        }
                    }
                }
            }
        }

        foreach (array_keys($studentIds) as $idSiswa) {
            $email = "siswa{$idSiswa}@gmail.com";
            $nisn = str_pad(1234567890 + $idSiswa, 10, '0', STR_PAD_LEFT);
            
            $user = \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Siswa Dummy {$idSiswa}",
                    'password' => \Illuminate\Support\Facades\Hash::make('siswa123'),
                    'role' => 'siswa',
                    'nisn' => $nisn,
                ]
            );

            $existingSiswa = \Illuminate\Support\Facades\DB::table('calon_siswa')->where('id_siswa', $idSiswa)->first();
            if (!$existingSiswa) {
                \Illuminate\Support\Facades\DB::table('calon_siswa')->insert([
                    'id_siswa' => $idSiswa,
                    'user_id' => $user->id,
                    'nisn' => $nisn,
                    'nama' => "Siswa Dummy {$idSiswa}",
                    'jenis_kelamin' => $idSiswa % 2 === 0 ? 'P' : 'L',
                    'tempat_lahir' => 'Pontianak',
                    'tanggal_lahir' => '2008-05-15',
                    'agama' => 'Islam',
                    'kewarganegaraan' => 'WNI',
                    'alamat' => "Jl. Merdeka No. {$idSiswa}, Pontianak",
                    'kelurahan' => 'Tengah',
                    'kecamatan' => 'Pontianak Kota',
                    'kota' => 'Pontianak',
                    'provinsi' => 'Kalimantan Barat',
                    'tinggi_badan' => 165,
                    'penyakit' => 'Tidak ada',
                    'jumlah_saudara' => 2,
                    'anak_ke' => 1,
                    'no_hp' => "08123456789{$idSiswa}",
                    'email' => $email,
                    'jurusan' => $jurusans[$idSiswa % 3],
                    'nama_ayah' => "Ayah Siswa {$idSiswa}",
                    'nama_ibu' => "Ibu Siswa {$idSiswa}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $pendaftaran = \App\Models\Pendaftaran::firstOrCreate(
                ['id_siswa' => $idSiswa],
                [
                    'tanggal_daftar' => now(),
                    'status' => 'verifikasi',
                ]
            );

            foreach ($berkasFiles as $bf) {
                if ($bf['id_siswa'] === $idSiswa) {
                    \App\Models\Berkas::firstOrCreate(
                        [
                            'id_daftar' => $pendaftaran->id_daftar,
                            'nama_berkas' => $bf['nama_berkas']
                        ],
                        [
                            'file' => $bf['file']
                        ]
                    );
                }
            }

            $angsuran = 1;
            foreach ($pembayaranFiles as $pf) {
                if ($pf['id_siswa'] === $idSiswa) {
                    $exists = \App\Models\Pembayaran::where('id_daftar', $pendaftaran->id_daftar)
                        ->where('angsuran_ke', $angsuran)
                        ->exists();
                    
                    if (!$exists) {
                        \App\Models\Pembayaran::create([
                            'id_daftar' => $pendaftaran->id_daftar,
                            'angsuran_ke' => $angsuran,
                            'metode_pembayaran' => 'transfer',
                            'tanggal_bayar' => now(),
                            'jumlah' => $angsuran === 1 ? 1500000 : 500000,
                            'bukti_bayar' => $pf['file'],
                            'status' => 'menunggu'
                        ]);
                    }
                    $angsuran++;
                }
            }
        }
    }
}
