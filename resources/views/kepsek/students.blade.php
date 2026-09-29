@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('kepsek.dashboard') }}" class="sidebar-link">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('kepsek.students.index') }}" class="sidebar-link active">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('kepsek.payments.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-money-bill-wave"></i> Data Pembayaran
                </a>
                <a href="{{ route('kepsek.export') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </a>
            </nav>
        </div>
        
        <div style="margin-top: auto;">
            <p style="font-size: 0.8rem; color: var(--gray-400); text-align: center;">
                Kepala Sekolah Portal<br>SMK Mandiri Pontianak
            </p>
        </div>
    </aside>

    <!-- Content -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Data Calon Siswa Terdaftar</h1>
                <p style="color: var(--gray-600);">Daftar seluruh calon siswa yang mendaftar PPDB online di SMK Mandiri.</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form action="{{ route('kepsek.students.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="search" class="form-label" style="font-size: 0.8rem;">Cari Nama / NISN</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Nama, NISN, atau Email..." value="{{ request('search') }}" style="padding: 0.5rem 0.75rem; font-size: 0.85rem; background: white;">
                </div>
                
                <div style="width: 150px;">
                    <label for="jurusan" class="form-label" style="font-size: 0.8rem;">Jurusan</label>
                    <select name="jurusan" id="jurusan" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.85rem; background: white;">
                        <option value="">Semua</option>
                        <option value="Animasi" {{ request('jurusan') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                        <option value="Akuntansi" {{ request('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="Pemasaran" {{ request('jurusan') == 'Pemasaran' ? 'selected' : '' }}>Pemasaran</option>
                    </select>
                </div>

                <div style="width: 150px;">
                    <label for="status" class="form-label" style="font-size: 0.8rem;">Status Seleksi</label>
                    <select name="status" id="status" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.85rem; background: white;">
                        <option value="">Semua</option>
                        <option value="perlu_verifikasi" {{ request('status') == 'perlu_verifikasi' ? 'selected' : '' }}>Perlu Verifikasi</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>



                <div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('kepsek.students.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Student List Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Jurusan</th>

                        <th>Status Seleksi</th>
                        <th>Status Administrasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $pendaftaran = $student->pendaftaran;
                            $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : null;
                            $totalBiaya = 3100000;
                            $sudahDibayar = $pembayaran ? $pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
                            $hasPending = $pembayaran ? $pembayaran->where('status', 'menunggu')->count() > 0 : false;
                            
                            if ($sudahDibayar >= $totalBiaya && !$hasPending) {
                                $statusLabel = 'Lunas';
                                $badgeClass = 'valid';
                            } elseif ($hasPending) {
                                $statusLabel = 'Menunggu';
                                $badgeClass = 'menunggu';
                            } elseif ($sudahDibayar > 0) {
                                $statusLabel = 'Cicilan';
                                $badgeClass = 'verifikasi';
                            } else {
                                $statusLabel = 'Belum Bayar';
                                $badgeClass = 'ditolak';
                            }
                        @endphp
                        <tr>
                            <td>{{ $student->nisn }}</td>
                            <td>
                                <strong>{{ $student->nama }}</strong>
                                <span style="display: block; font-size: 0.75rem; color: var(--gray-400);">{{ $student->email }}</span>
                            </td>
                            <td>{{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $student->jurusan }}</td>

                            <td>
                                @if($pendaftaran)
                                    <span class="badge badge-{{ $pendaftaran->status }}">{{ $pendaftaran->status }}</span>
                                @else
                                    <span class="badge badge-ditolak">No Daftar</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <a href="{{ route('kepsek.students.show', $student->id_siswa) }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--gray-400); font-style: italic; padding: 2rem;">
                                Tidak ada data calon siswa yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $students->links() }}
        </div>
    </main>
</div>
@endsection
