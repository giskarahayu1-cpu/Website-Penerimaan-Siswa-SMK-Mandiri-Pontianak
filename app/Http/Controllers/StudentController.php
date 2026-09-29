<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\Berkas;
use App\Models\Pembayaran;
use App\Models\Setting;
use App\Models\User;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'siswa') {
                return redirect()->route('admin.dashboard');
            }
            return $next($request);
        });
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->calonSiswa;

        if (!$siswa) {
            if (Setting::get('pendaftaran_status', 'dibuka') === 'ditutup') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Periode pendaftaran telah ditutup. Calon siswa baru yang belum melengkapi formulir tidak dapat masuk.');
            }
            return redirect()->route('student.register')->with('info', 'Silakan isi formulir pendaftaran terlebih dahulu.');
        }

        $pendaftaran = $siswa->pendaftaran;
        $berkas = $pendaftaran ? $pendaftaran->berkas : collect();
        $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : null;

        // Kelompokkan file dokumen yang telah diupload berdasarkan nama berkas
        $uploadedDocs = $berkas->pluck('file', 'nama_berkas')->toArray();

        // Check completeness of data diri
        $requiredFields = [
            'nama', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'kewarganegaraan', 'alamat', 'kelurahan', 'kecamatan', 'kota',
            'provinsi', 'no_hp', 'jurusan', 'nama_ayah', 'nama_ibu',
            'jumlah_saudara', 'tinggi_badan', 'penyakit', 'anak_ke'
        ];
        
        $isDataDiriLengkap = true;
        foreach ($requiredFields as $field) {
            if (empty($siswa->$field)) {
                $isDataDiriLengkap = false;
                break;
            }
        }

        // Check completeness of mandatory documents
        $requiredDocs = ['ijazah', 'kk', 'akta', 'ktp orang tua'];
        $missingDocs = [];
        foreach ($requiredDocs as $doc) {
            if (!isset($uploadedDocs[$doc])) {
                $missingDocs[] = ucwords($doc);
            }
        }
        $isBerkasLengkap = empty($missingDocs);
        $isLengkap = $isDataDiriLengkap && $isBerkasLengkap;

        $tab = $request->query('tab', 'pendaftaran');
        $pendaftaranStatus = Setting::get('pendaftaran_status', 'dibuka');

        if ($tab === 'administrasi' && !$isLengkap) {
            $missingItems = [];
            if (!$isDataDiriLengkap) {
                $missingItems[] = 'data diri dengan lengkap';
            }
            if (!$isBerkasLengkap) {
                $missingItems[] = 'semua berkas persyaratan wajib (' . implode(', ', $missingDocs) . ')';
            }
            
            $msg = 'Anda tidak dapat mengakses halaman Administrasi. Harap lengkapi ' . implode(' dan ', $missingItems) . ' terlebih dahulu.';
            return redirect()->route('student.dashboard', ['tab' => 'pendaftaran'])->with('error', $msg);
        }

        return view('student.dashboard', compact('siswa', 'pendaftaran', 'uploadedDocs', 'pembayaran', 'tab', 'pendaftaranStatus', 'isLengkap', 'isDataDiriLengkap', 'isBerkasLengkap', 'missingDocs'));
    }

    public function showRegisterForm()
    {
        if (Setting::get('pendaftaran_status', 'dibuka') === 'ditutup') {
            return redirect()->route('login')->with('error', 'Periode pendaftaran online telah ditutup.');
        }
        if (Auth::user()->calonSiswa) {
            return redirect()->route('student.dashboard');
        }
        return view('student.register');
    }

    public function storeRegisterForm(Request $request)
    {
        if (Setting::get('pendaftaran_status', 'dibuka') === 'ditutup') {
            return redirect()->route('login')->with('error', 'Periode pendaftaran online telah ditutup.');
        }
        if (Auth::user()->calonSiswa) {
            return redirect()->route('student.dashboard');
        }

        $request->validate([
            'nisn' => ['required', 'string', 'size:10', 'unique:calon_siswa,nisn'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'agama' => ['required', 'string', 'max:30'],
            'kewarganegaraan' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
            'kelurahan' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'tinggi_badan' => ['required', 'integer'],
            'penyakit' => ['required', 'string', 'max:150'],
            'jumlah_saudara' => ['required', 'integer'],
            'anak_ke' => ['required', 'integer', 'min:1'],
            'no_hp' => ['required', 'string', 'max:15'],
            'jurusan' => ['required', 'in:Akuntansi,Animasi,Pemasaran'],
            
            // Ortu / Wali
            'nama_ayah' => ['required', 'string', 'max:100'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:100'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:50'],
            'penghasilan_ayah' => ['nullable', 'string', 'max:100'],
            'nama_ibu' => ['required', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:100'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:50'],
            'penghasilan_ibu' => ['nullable', 'string', 'max:100'],
            'no_hp_ortu' => ['nullable', 'string', 'max:20', 'different:no_hp'],
            'nama_wali' => ['nullable', 'string', 'max:100'],
            'pekerjaan_wali' => ['nullable', 'string', 'max:100'],
            'alamat_wali' => ['nullable', 'string'],
            'no_hp_wali' => ['nullable', 'string', 'max:20'],
            
            // Files
            'file_ijazah' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_akta' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_ktp_ortu' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_kps' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_kip' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'pernyataan' => ['required', 'accepted'],
        ], [
            'no_hp_ortu.different' => 'Nomor HP Orang Tua tidak boleh sama dengan Nomor HP Siswa.',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => Auth::id(),
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'alamat' => $request->alamat,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'tinggi_badan' => $request->tinggi_badan,
            'penyakit' => $request->penyakit,
            'jumlah_saudara' => $request->jumlah_saudara,
            'anak_ke' => $request->anak_ke,
            'no_hp' => $request->no_hp,
            'email' => Auth::user()->email,
            'jurusan' => $request->jurusan,
            
            // Ortu / Wali
            'nama_ayah' => $request->nama_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'penghasilan_ayah' => $request->penghasilan_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'penghasilan_ibu' => $request->penghasilan_ibu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'nama_wali' => $request->nama_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'alamat_wali' => $request->alamat_wali,
            'no_hp_wali' => $request->no_hp_wali,
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        // Upload files
        $fileFields = [
            'file_ijazah' => 'ijazah',
            'file_kk' => 'kk',
            'file_akta' => 'akta',
            'file_ktp_ortu' => 'ktp orang tua',
            'file_kps' => 'kps',
            'file_kip' => 'kip',
        ];

        foreach ($fileFields as $inputName => $dbName) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $filename = 'berkas_' . str_replace(' ', '_', $dbName) . '_' . $siswa->id_siswa . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/berkas', $filename, 'public');

                Berkas::create([
                    'id_daftar' => $pendaftaran->id_daftar,
                    'nama_berkas' => $dbName,
                    'file' => $path,
                ]);
            }
        }

        return redirect()->route('student.dashboard')->with('success', 'Formulir pendaftaran berhasil disimpan!');
    }

    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'berkas_type' => ['required', 'in:ijazah,kk,akta,ktp orang tua,kps,kip'],
            'file_dokumen' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $user = Auth::user();
        $siswa = $user->calonSiswa;

        if (!$siswa || !$siswa->pendaftaran) {
            return redirect()->route('student.dashboard')->with('error', 'Formulir pendaftaran belum terisi.');
        }

        $pendaftaran = $siswa->pendaftaran;
        $type = $request->berkas_type;

        // Handle File upload
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = 'berkas_' . str_replace(' ', '_', $type) . '_' . $siswa->id_siswa . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/berkas', $filename, 'public');

            // Save to database
            Berkas::updateOrCreate(
                [
                    'id_daftar' => $pendaftaran->id_daftar,
                    'nama_berkas' => $type
                ],
                [
                    'file' => $path
                ]
            );

            return redirect()->route('student.dashboard')->with('success', 'Berkas ' . ucfirst($type) . ' berhasil diperbarui!');
        }

        return redirect()->route('student.dashboard')->with('error', 'Gagal mengunggah berkas.');
    }

    public function uploadPembayaran(Request $request)
    {
        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_bayar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'angsuran_ke' => ['required', 'integer', 'between:1,4'],
            'metode_pembayaran' => ['required', 'string', 'in:transfer,tunai'],
        ]);

        $user = Auth::user();
        $siswa = $user->calonSiswa;

        if (!$siswa || !$siswa->pendaftaran) {
            return redirect()->route('student.dashboard')->with('error', 'Formulir pendaftaran belum terisi.');
        }

        $pendaftaran = $siswa->pendaftaran;

        // Check completeness of data diri and berkas before payment
        $berkas = $pendaftaran ? $pendaftaran->berkas : collect();
        $uploadedDocs = $berkas->pluck('file', 'nama_berkas')->toArray();

        $requiredFields = [
            'nama', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'kewarganegaraan', 'alamat', 'kelurahan', 'kecamatan', 'kota',
            'provinsi', 'no_hp', 'jurusan', 'nama_ayah', 'nama_ibu',
            'jumlah_saudara', 'tinggi_badan', 'penyakit', 'anak_ke'
        ];
        
        $isDataDiriLengkap = true;
        foreach ($requiredFields as $field) {
            if (empty($siswa->$field)) {
                $isDataDiriLengkap = false;
                break;
            }
        }

        $requiredDocs = ['ijazah', 'kk', 'akta', 'ktp orang tua'];
        $missingDocs = [];
        foreach ($requiredDocs as $doc) {
            if (!isset($uploadedDocs[$doc])) {
                $missingDocs[] = ucwords($doc);
            }
        }
        $isBerkasLengkap = empty($missingDocs);

        if (!$isDataDiriLengkap || !$isBerkasLengkap) {
            $missingItems = [];
            if (!$isDataDiriLengkap) {
                $missingItems[] = 'data diri dengan lengkap';
            }
            if (!$isBerkasLengkap) {
                $missingItems[] = 'semua berkas persyaratan wajib (' . implode(', ', $missingDocs) . ')';
            }
            
            $msg = 'Anda tidak dapat mengunggah bukti pembayaran. Harap lengkapi ' . implode(' dan ', $missingItems) . ' terlebih dahulu.';
            return redirect()->route('student.dashboard', ['tab' => 'pendaftaran'])->with('error', $msg);
        }

        // Hitung sisa tagihan di server-side
        $totalBiaya = 3100000;
        $sudahDibayar = $pendaftaran->pembayaran()
            ->whereIn('status', ['valid', 'menunggu'])
            ->where('angsuran_ke', '!=', $request->angsuran_ke)
            ->sum('jumlah');
        $sisaBayar = max(0, $totalBiaya - $sudahDibayar);

        $angsuranKe = $request->angsuran_ke;
        $jumlah = $request->jumlah;

        if ($angsuranKe == 1) {
            if ($jumlah != 1500000) {
                return redirect()->route('student.dashboard', ['tab' => 'administrasi'])->with('error', 'Pembayaran ke-1 harus nominal Rp 1.500.000.');
            }
        } elseif ($angsuranKe == 2 || $angsuranKe == 3) {
            $maxAllowed = min(500000, $sisaBayar);
            $minAllowed = min(50000, $sisaBayar);
            if ($jumlah != $sisaBayar && ($jumlah < $minAllowed || $jumlah > $maxAllowed)) {
                return redirect()->route('student.dashboard', ['tab' => 'administrasi'])->with('error', "Pembayaran ke-{$angsuranKe} harus di antara Rp " . number_format($minAllowed, 0, ',', '.') . " dan Rp " . number_format($maxAllowed, 0, ',', '.') . " atau langsung melunasi sisa tagihan sebesar Rp " . number_format($sisaBayar, 0, ',', '.') . ".");
            }
        } elseif ($angsuranKe == 4) {
            if ($jumlah != $sisaBayar) {
                return redirect()->route('student.dashboard', ['tab' => 'administrasi'])->with('error', 'Pembayaran ke-4 harus melunasi sisa tagihan sebesar Rp ' . number_format($sisaBayar, 0, ',', '.') . '.');
            }
        }

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = 'bukti_bayar_' . $siswa->id_siswa . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/pembayaran', $filename, 'public');

            Pembayaran::updateOrCreate(
                [
                    'id_daftar' => $pendaftaran->id_daftar,
                    'angsuran_ke' => $request->angsuran_ke
                ],
                [
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'tanggal_bayar' => now(),
                    'jumlah' => $request->jumlah,
                    'bukti_bayar' => $path,
                    'status' => 'menunggu',
                    'is_read' => false
                ]
            );

            return redirect()->route('student.dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi oleh panitia.');
        }

        return redirect()->route('student.dashboard')->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    public function printProof()
    {
        $user = Auth::user();
        $siswa = $user->calonSiswa;

        if (!$siswa || !$siswa->pendaftaran || $siswa->pendaftaran->status !== 'diterima') {
            return redirect()->route('student.dashboard')->with('error', 'Anda belum dapat mencetak bukti pendaftaran.');
        }

        $pendaftaran = $siswa->pendaftaran;

        return view('student.print', compact('siswa', 'pendaftaran'));
    }

    public function edit()
    {
        $siswa = Auth::user()->calonSiswa;

        if (!$siswa) {
            return redirect()->route('student.register')->with('info', 'Silakan isi formulir pendaftaran terlebih dahulu.');
        }

        if ($siswa->pendaftaran && $siswa->pendaftaran->status === 'diterima') {
            return redirect()->route('student.dashboard')->with('error', 'Status pendaftaran Anda sudah diterima. Anda tidak dapat mengedit data diri lagi.');
        }

        return view('student.edit', compact('siswa'));
    }

    public function update(Request $request)
    {
        $siswa = Auth::user()->calonSiswa;

        if (!$siswa) {
            return redirect()->route('student.register');
        }

        if ($siswa->pendaftaran && $siswa->pendaftaran->status === 'diterima') {
            return redirect()->route('student.dashboard')->with('error', 'Status pendaftaran Anda sudah diterima. Anda tidak dapat mengedit data diri lagi.');
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nisn' => ['required', 'string', 'size:10', 'unique:calon_siswa,nisn,' . $siswa->id_siswa . ',id_siswa', 'unique:users,nisn,' . Auth::id()],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'agama' => ['required', 'string', 'max:30'],
            'kewarganegaraan' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
            'kelurahan' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'tinggi_badan' => ['required', 'integer'],
            'penyakit' => ['required', 'string', 'max:150'],
            'jumlah_saudara' => ['required', 'integer'],
            'anak_ke' => ['required', 'integer', 'min:1'],
            'no_hp' => ['required', 'string', 'max:15'],
            'jurusan' => ['required', 'in:Akuntansi,Animasi,Pemasaran'],
            
            // Ortu / Wali
            'nama_ayah' => ['required', 'string', 'max:100'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:100'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:50'],
            'penghasilan_ayah' => ['nullable', 'string', 'max:100'],
            'nama_ibu' => ['required', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:100'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:50'],
            'penghasilan_ibu' => ['nullable', 'string', 'max:100'],
            'no_hp_ortu' => ['nullable', 'string', 'max:20', 'different:no_hp'],
            'nama_wali' => ['nullable', 'string', 'max:100'],
            'pekerjaan_wali' => ['nullable', 'string', 'max:100'],
            'alamat_wali' => ['nullable', 'string'],
            'no_hp_wali' => ['nullable', 'string', 'max:20'],
        ], [
            'no_hp_ortu.different' => 'Nomor HP Orang Tua tidak boleh sama dengan Nomor HP Siswa.',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'kewarganegaraan' => $request->kewarganegaraan,
            'alamat' => $request->alamat,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'tinggi_badan' => $request->tinggi_badan,
            'penyakit' => $request->penyakit,
            'jumlah_saudara' => $request->jumlah_saudara,
            'anak_ke' => $request->anak_ke,
            'no_hp' => $request->no_hp,
            'jurusan' => $request->jurusan,
            
            // Ortu / Wali
            'nama_ayah' => $request->nama_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'penghasilan_ayah' => $request->penghasilan_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'penghasilan_ibu' => $request->penghasilan_ibu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'nama_wali' => $request->nama_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'alamat_wali' => $request->alamat_wali,
            'no_hp_wali' => $request->no_hp_wali,
        ]);

        // Sync name, nisn, and no_hp in users table
        if ($siswa->user) {
            $siswa->user->update([
                'name' => $request->nama,
                'nisn' => $request->nisn,
                'no_hp' => $request->no_hp,
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Data diri Anda berhasil diperbarui.');
    }

    public function markNotificationsAsRead()
    {
        $user = Auth::user();
        $siswa = $user->calonSiswa;
        if ($siswa && $siswa->pendaftaran) {
            $siswa->pendaftaran->pembayaran()->where('is_read', false)->update(['is_read' => true]);
        }
        return response()->json(['success' => true]);
    }
}
