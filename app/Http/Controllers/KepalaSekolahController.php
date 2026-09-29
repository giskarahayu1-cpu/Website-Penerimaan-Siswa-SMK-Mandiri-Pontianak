<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class KepalaSekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'kepsek') {
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('student.dashboard');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Statistik
        $totalPendaftar = CalonSiswa::count();
        $totalBaruDaftarAkun = \App\Models\User::where('role', 'siswa')
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

        // Standarisasi jumlah status dan jurusan untuk menghindari error kunci array tidak terdefinisi
        $statuses = ['menunggu' => 0, 'verifikasi' => 0, 'diterima' => 0, 'ditolak' => 0];
        foreach ($statusCounts as $status => $count) {
            $statuses[$status] = $count;
        }

        $jurusans = ['Akuntansi' => 0, 'Animasi' => 0, 'Pemasaran' => 0];
        foreach ($jurusanCounts as $jurusan => $count) {
            $jurusans[$jurusan] = $count;
        }

        // Data diagram simulasi
        $chartData = [
            '2022' => 120,
            '2023' => 160,
            '2024' => 144,
            '2025' => 176,
            '2026' => $totalPendaftar
        ];

        // Pendaftar terbaru
        $recentRegistrants = CalonSiswa::with('pendaftaran')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('kepsek.dashboard', compact('totalPendaftar', 'totalBaruDaftarAkun', 'statuses', 'jurusans', 'chartData', 'recentRegistrants'));
    }

    public function students(Request $request)
    {
        $query = CalonSiswa::with('pendaftaran.pembayaran');

        // Penyaringan pencarian nama/NISN/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Penyaringan jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }



        // Penyaringan status seleksi
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'perlu_verifikasi') {
                $query->whereHas('pendaftaran', function($q) {
                    $q->whereIn('status', ['menunggu', 'verifikasi']);
                });
            } else {
                $query->whereHas('pendaftaran', function($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('kepsek.students', compact('students'));
    }

    public function showStudent($id_siswa)
    {
        $siswa = CalonSiswa::with(['pendaftaran.berkas', 'pendaftaran.pembayaran'])->findOrFail($id_siswa);
        $pendaftaran = $siswa->pendaftaran;
        
        $berkas = $pendaftaran ? $pendaftaran->berkas : collect();
        $uploadedDocs = $berkas->pluck('file', 'nama_berkas')->toArray();
        $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : null;

        return view('kepsek.detail', compact('siswa', 'pendaftaran', 'uploadedDocs', 'pembayaran'));
    }

    public function payments(Request $request)
    {
        $query = Pembayaran::with('pendaftaran.calonSiswa');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('kepsek.payments', compact('payments'));
    }

    public function exportData()
    {
        $students = CalonSiswa::with(['pendaftaran.pembayaran'])->orderBy('created_at', 'desc')->get();

        return view('admin.export', compact('students')); // Menggunakan kembali layout laporan cetak admin
    }
}
