<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Setting;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('student.dashboard');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Statistics
        $totalPendaftar = CalonSiswa::count();
        $totalBaruDaftarAkun = User::where('role', 'siswa')
            ->whereDoesntHave('calonSiswa')
            ->count();
        $statusCounts = Pendaftaran::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $jurusanCounts = CalonSiswa::select('jurusan', DB::raw('count(*) as total'))
            ->groupBy('jurusan')
            ->pluck('total', 'jurusan')
            ->toArray();

        // Standardise status and jurusan counts to avoid undefined array key errors
        $statuses = ['menunggu' => 0, 'verifikasi' => 0, 'diterima' => 0, 'ditolak' => 0];
        foreach ($statusCounts as $status => $count) {
            $statuses[$status] = $count;
        }

        $jurusans = ['Akuntansi' => 0, 'Animasi' => 0, 'Pemasaran' => 0];
        foreach ($jurusanCounts as $jurusan => $count) {
            $jurusans[$jurusan] = $count;
        }

        // Mock chart data for year-by-year PPDB count (from Giska's proposal text: 2022: +25%, 2023: +33.3%, 2024: -10%, 2025: +22.2%)
        $chartData = [
            '2022' => 120,
            '2023' => 160,
            '2024' => 144,
            '2025' => 176,
            '2026' => $totalPendaftar
        ];

        // Recent registrants
        $recentRegistrants = CalonSiswa::with('pendaftaran')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendaftaranStatus = Setting::get('pendaftaran_status', 'dibuka');

        return view('admin.dashboard', compact('totalPendaftar', 'totalBaruDaftarAkun', 'statuses', 'jurusans', 'chartData', 'recentRegistrants', 'pendaftaranStatus'));
    }

    public function students(Request $request)
    {
        $query = User::where('role', 'siswa')->with('calonSiswa.pendaftaran.pembayaran');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('calonSiswa', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        // Registration status filter
        if ($request->filled('status_registrasi')) {
            if ($request->status_registrasi === 'registrasi') {
                $query->whereDoesntHave('calonSiswa');
            } elseif ($request->status_registrasi === 'sudah_form') {
                $query->whereHas('calonSiswa');
            }
        }

        // Jurusan filter
        if ($request->filled('jurusan')) {
            $query->whereHas('calonSiswa', function($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }


        // Status filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'perlu_verifikasi') {
                $query->whereHas('calonSiswa.pendaftaran', function($q) {
                    $q->whereIn('status', ['menunggu', 'verifikasi']);
                });
            } else {
                $query->whereHas('calonSiswa.pendaftaran', function($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nisn' => ['required', 'string', 'size:10', 'unique:users,nisn', 'unique:calon_siswa,nisn'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:15'],
            'jurusan' => ['required', 'in:Akuntansi,Animasi,Pemasaran'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $user->id,
            'nisn' => $request->nisn,
            'nama' => $request->name,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jurusan' => $request->jurusan,
        ]);

        Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Calon Siswa baru berhasil didaftarkan secara manual.');
    }

    public function showStudent($id_siswa)
    {
        $siswa = CalonSiswa::with(['pendaftaran.berkas', 'pendaftaran.pembayaran'])->findOrFail($id_siswa);
        $pendaftaran = $siswa->pendaftaran;
        
        $berkas = $pendaftaran ? $pendaftaran->berkas : collect();
        $uploadedDocs = $berkas->pluck('file', 'nama_berkas')->toArray();
        $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : null;

        return view('admin.detail', compact('siswa', 'pendaftaran', 'uploadedDocs', 'pembayaran'));
    }

    public function editStudent($id_siswa)
    {
        $siswa = CalonSiswa::findOrFail($id_siswa);
        return view('admin.edit', compact('siswa'));
    }

    public function updateStudent(Request $request, $id_siswa)
    {
        $siswa = CalonSiswa::findOrFail($id_siswa);

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nisn' => ['required', 'string', 'size:10', 'unique:calon_siswa,nisn,' . $id_siswa . ',id_siswa', 'unique:users,nisn,' . ($siswa->user_id ?? 'NULL')],
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

        return redirect()->route('admin.students.show', $id_siswa)->with('success', 'Data calon siswa berhasil diperbarui.');
    }

    public function deleteStudent($id_siswa)
    {
        $siswa = CalonSiswa::findOrFail($id_siswa);
        $user = $siswa->user;

        // Delete records
        $siswa->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.students.index')->with('success', 'Data calon siswa berhasil dihapus.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->calonSiswa) {
            $user->calonSiswa->delete();
        }
        $user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Akun pendaftar berhasil dihapus.');
    }

    public function verifyStudent(Request $request, $id_siswa)
    {
        $siswa = CalonSiswa::findOrFail($id_siswa);
        $pendaftaran = $siswa->pendaftaran;

        if (!$pendaftaran) {
            return back()->with('error', 'Data pendaftaran belum dibuat.');
        }

        $request->validate([
            'status' => ['required', 'in:menunggu,verifikasi,diterima,ditolak'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $previousStatus = $pendaftaran->status;
        $keterangan = $request->keterangan ?? $pendaftaran->keterangan;
        if (empty($keterangan) || ($request->status === 'diterima' && $previousStatus !== 'diterima')) {
            if ($request->status === 'diterima') {
                $keterangan = 'Selamat, Anda dinyatakan diterima sebagai calon siswa.';
            } elseif ($request->status === 'ditolak') {
                $keterangan = 'Mohon maaf, berkas dan persyaratan Anda belum memenuhi kualifikasi.';
            } elseif ($request->status === 'verifikasi') {
                $keterangan = 'Dokumen dan berkas pendaftaran sedang dalam proses verifikasi.';
            } else {
                $keterangan = 'Menunggu verifikasi berkas oleh panitia PPDB.';
            }
        }

        $pendaftaran->update([
            'status' => $request->status,
            'keterangan' => $keterangan,
        ]);

        if ($request->status === 'diterima' && $previousStatus !== 'diterima') {
            if ($siswa->no_hp) {
                $namaSiswa = ucwords(strtolower($siswa->nama));
                $message = "PPDB SMK Mandiri Pontianak\n\n"
                    . "PENGUMUMAN HASIL SELEKSI\n\n"
                    . "Halo, {$namaSiswa}.\n\n"
                    . "Selamat!\n\n"
                    . "Berdasarkan hasil seleksi PPDB, Anda dinyatakan DITERIMA sebagai peserta didik baru di SMK Mandiri Pontianak.\n\n"
                    . "Silakan login ke sistem PPDB untuk melihat informasi selengkapnya.\n\n"
                    . "Terima kasih.";

                $fonnteService = app(\App\Services\FonnteService::class);
                $fonnteService->sendMessage($siswa->no_hp, $message);
            }
        }

        return redirect()->route('admin.students.show', $id_siswa)->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function payments(Request $request)
    {
        $query = Pembayaran::with(['pendaftaran.calonSiswa', 'pendaftaran.pembayaran']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.payments', compact('payments'));
    }

    public function verifyPayment(Request $request, $id_pembayaran)
    {
        $pembayaran = Pembayaran::with('pendaftaran.calonSiswa')->findOrFail($id_pembayaran);

        $request->validate([
            'status' => ['required', 'in:menunggu,valid,ditolak'],
        ]);

        $previousStatus = $pembayaran->status;

        $pembayaran->update([
            'status' => $request->status,
            'is_read' => false,
        ]);

        // If payment is valid and files are complete, admin can decide to change pendaftaran status later.
        // Let's set the pendaftaran status to 'verifikasi' automatically when a payment is validated.
        $pendaftaran = $pembayaran->pendaftaran;
        if ($pendaftaran && $request->status === 'valid') {
            if ($pendaftaran->status === 'menunggu') {
                $pendaftaran->update(['status' => 'verifikasi']);
            }
        }

        // WhatsApp notification to student when payment is verified
        $siswa = $pendaftaran ? $pendaftaran->calonSiswa : null;
        if ($siswa && $siswa->no_hp && $request->status !== $previousStatus) {
            $namaSiswa = ucwords(strtolower($siswa->nama));
            $nominalFormatted = 'Rp ' . number_format($pembayaran->jumlah, 0, ',', '.');
            $fonnteService = app(FonnteService::class);

            if ($request->status === 'valid') {
                $message = "PPDB SMK Mandiri Pontianak\n\n"
                    . "VERIFIKASI PEMBAYARAN: VALID\n\n"
                    . "Halo, {$namaSiswa}.\n\n"
                    . "Pembayaran PPDB Anda untuk Angsuran Ke-{$pembayaran->angsuran_ke} sebesar {$nominalFormatted} telah berhasil DIVERIFIKASI (VALID).\n\n"
                    . "Silakan login ke portal PPDB untuk melihat rincian administrasi dan proses pendaftaran selanjutnya.\n\n"
                    . "Terima kasih.";
                $fonnteService->sendMessage($siswa->no_hp, $message);
            } elseif ($request->status === 'ditolak') {
                $message = "PPDB SMK Mandiri Pontianak\n\n"
                    . "PEMBERITAHUAN PEMBAYARAN: DITOLAK\n\n"
                    . "Halo, {$namaSiswa}.\n\n"
                    . "Mohon maaf, bukti transfer pembayaran PPDB Anda untuk Angsuran Ke-{$pembayaran->angsuran_ke} sebesar {$nominalFormatted} DITOLAK karena tidak valid atau tidak terbaca.\n\n"
                    . "Silakan login ke portal PPDB dan unggah ulang bukti transfer yang sah.\n\n"
                    . "Terima kasih.";
                $fonnteService->sendMessage($siswa->no_hp, $message);
            }
        }

        return redirect()->route('admin.payments.index')->with('success', 'Status verifikasi pembayaran berhasil diubah.');
    }

    public function showPayment($id_pembayaran)
    {
        $payment = Pembayaran::with(['pendaftaran.calonSiswa', 'pendaftaran.pembayaran'])->findOrFail($id_pembayaran);
        $pendaftaran = $payment->pendaftaran;
        $siswa = $pendaftaran ? $pendaftaran->calonSiswa : null;
        $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : collect();
        return view('admin.payment_detail', compact('payment', 'pendaftaran', 'siswa', 'pembayaran'));
    }

    public function savePaymentSlot(Request $request, $id_siswa)
    {
        $siswa = CalonSiswa::findOrFail($id_siswa);
        $pendaftaran = $siswa->pendaftaran;
        
        if (!$pendaftaran) {
            return back()->with('error', 'Siswa belum memiliki data pendaftaran.');
        }
        
        $request->validate([
            'angsuran_ke' => ['required', 'integer', 'between:1,4'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:menunggu,valid,ditolak'],
            'tanggal_bayar' => ['required', 'date'],
            'bukti_bayar' => ['nullable', 'image', 'max:2048'],
            'metode_pembayaran' => ['required', 'string', 'in:transfer,tunai'],
        ]);
        
        $path = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = 'bukti_bayar_' . $id_siswa . '_' . $request->angsuran_ke . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/pembayaran', $filename, 'public');
        } else {
            // keep old if exists, or use manual payment placeholder
            $existing = Pembayaran::where('id_daftar', $pendaftaran->id_daftar)->where('angsuran_ke', $request->angsuran_ke)->first();
            $path = $existing ? $existing->bukti_bayar : 'uploads/pembayaran/manual.png';
        }
        
        $jumlah = $request->angsuran_ke == 1 ? 1500000 : $request->jumlah;

        $existing = Pembayaran::where('id_daftar', $pendaftaran->id_daftar)->where('angsuran_ke', $request->angsuran_ke)->first();
        $previousStatus = $existing ? $existing->status : null;
        
        Pembayaran::updateOrCreate(
            [
                'id_daftar' => $pendaftaran->id_daftar,
                'angsuran_ke' => $request->angsuran_ke
            ],
            [
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah' => $jumlah,
                'status' => $request->status,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti_bayar' => $path,
                'is_read' => false
            ]
        );
        
        $validPaymentsCount = Pembayaran::where('id_daftar', $pendaftaran->id_daftar)->where('status', 'valid')->count();
        if ($validPaymentsCount > 0 && $pendaftaran->status === 'menunggu') {
            $pendaftaran->update(['status' => 'verifikasi']);
        }

        // WhatsApp notification to student if status changed to valid/ditolak
        if ($siswa->no_hp && $request->status !== $previousStatus) {
            $namaSiswa = ucwords(strtolower($siswa->nama));
            $nominalFormatted = 'Rp ' . number_format($jumlah, 0, ',', '.');
            $fonnteService = app(FonnteService::class);

            if ($request->status === 'valid') {
                $message = "PPDB SMK Mandiri Pontianak\n\n"
                    . "VERIFIKASI PEMBAYARAN: VALID\n\n"
                    . "Halo, {$namaSiswa}.\n\n"
                    . "Pembayaran PPDB Anda untuk Angsuran Ke-{$request->angsuran_ke} sebesar {$nominalFormatted} telah berhasil DIVERIFIKASI (VALID).\n\n"
                    . "Silakan login ke portal PPDB untuk melihat rincian administrasi Anda.\n\n"
                    . "Terima kasih.";
                $fonnteService->sendMessage($siswa->no_hp, $message);
            } elseif ($request->status === 'ditolak') {
                $message = "PPDB SMK Mandiri Pontianak\n\n"
                    . "PEMBERITAHUAN PEMBAYARAN: DITOLAK\n\n"
                    . "Halo, {$namaSiswa}.\n\n"
                    . "Mohon maaf, bukti transfer pembayaran PPDB Anda untuk Angsuran Ke-{$request->angsuran_ke} sebesar {$nominalFormatted} DITOLAK.\n\n"
                    . "Silakan login ke portal PPDB dan unggah ulang bukti transfer yang sah.\n\n"
                    . "Terima kasih.";
                $fonnteService->sendMessage($siswa->no_hp, $message);
            }
        }
        
        return back()->with('success', 'Data pembayaran angsuran berhasil diperbarui.');
    }

    public function exportData()
    {
        $students = CalonSiswa::with(['pendaftaran.pembayaran'])->orderBy('created_at', 'desc')->get();

        return view('admin.export', compact('students'));
    }

    public function togglePeriod(Request $request)
    {
        $request->validate([
            'status' => ['required', 'in:dibuka,ditutup']
        ]);

        Setting::set('pendaftaran_status', $request->status);

        $msg = $request->status === 'dibuka' ? 'Periode pendaftaran telah dibuka kembali.' : 'Periode pendaftaran resmi ditutup.';
        return back()->with('success', $msg);
    }

    public function updateBankSettings(Request $request)
    {
        $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
        ]);

        Setting::set('bank_name', $request->bank_name);
        Setting::set('bank_account_number', $request->bank_account_number);
        Setting::set('bank_account_name', $request->bank_account_name);

        return back()->with('success', 'Informasi rekening transfer bank berhasil diperbarui.');
    }
}
