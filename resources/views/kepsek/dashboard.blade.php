@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('kepsek.dashboard') }}" class="sidebar-link active">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('kepsek.students.index') }}" class="sidebar-link">
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
                <h1 style="color: var(--primary); font-size: 1.75rem;">Laporan Ringkasan PPDB</h1>
                <p style="color: var(--gray-600);">Halaman monitoring perkembangan penerimaan peserta didik baru (PPDB) SMK Mandiri.</p>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="stats-grid">
            <a href="{{ route('kepsek.students.index') }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $totalPendaftar }}</div>
                    <div class="stat-label">Total Pendaftar</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            </a>

            <div class="stat-card" style="cursor: default;">
                <div>
                    <div class="stat-num">{{ $totalBaruDaftarAkun }}</div>
                    <div class="stat-label">Baru Daftar Akun</div>
                </div>
                <div class="stat-icon" style="color: var(--info);"><i class="fa-solid fa-user-plus"></i></div>
            </div>

            <a href="{{ route('kepsek.students.index', ['status' => 'perlu_verifikasi']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['menunggu'] + $statuses['verifikasi'] }}</div>
                    <div class="stat-label">Belum Diverifikasi</div>
                </div>
                <div class="stat-icon" style="color: var(--warning);"><i class="fa-solid fa-user-clock"></i></div>
            </a>

            <a href="{{ route('kepsek.students.index', ['status' => 'diterima']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['diterima'] }}</div>
                    <div class="stat-label">Diterima</div>
                </div>
                <div class="stat-icon" style="color: var(--success);"><i class="fa-solid fa-user-check"></i></div>
            </a>

            <a href="{{ route('kepsek.students.index', ['status' => 'ditolak']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['ditolak'] }}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
                <div class="stat-icon" style="color: var(--danger);"><i class="fa-solid fa-user-xmark"></i></div>
            </a>
        </div>

        <div class="grid grid-2" style="margin-bottom: 2.5rem; align-items: start;">
            <!-- Chart Card -->
            <div class="card">
                <h3 class="card-title" style="color: var(--primary);"><i class="fa-solid fa-chart-column" style="margin-right: 0.5rem;"></i> Tren Pendaftar PPDB (Per Tahun)</h3>
                <p class="card-text" style="font-size: 0.85rem; margin-bottom: 1rem;">Perbandingan tren jumlah pendaftar dari tahun 2022 hingga tahun ajaran aktif 2026.</p>
                
                <div class="chart-container">
                    @foreach($chartData as $year => $value)
                        @php
                            $heightPct = min(100, ($value / 200) * 100);
                        @endphp
                        <div class="chart-bar-wrapper">
                            <div class="chart-bar" style="height: {{ $heightPct }}%;">
                                <span class="chart-val">{{ $value }}</span>
                            </div>
                            <span class="chart-label">{{ $year }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Major Distributions Card -->
            <div class="card">
                <h3 class="card-title" style="color: var(--primary); margin-bottom: 1.5rem;"><i class="fa-solid fa-school" style="margin-right: 0.5rem;"></i> Distribusi Jurusan Terdaftar</h3>
                <ul style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($jurusans as $jur => $count)
                        @php
                            $quotaPct = min(100, ($count / 64) * 100);
                        @endphp
                        <li>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem; font-size: 0.9rem;">
                                <strong>{{ $jur }}</strong>
                                <span style="color: var(--gray-600);">{{ $count }} / 64 Kuota ({{ number_format($quotaPct, 1) }}%)</span>
                            </div>
                            <div style="background: var(--gray-200); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: linear-gradient(to right, var(--primary), var(--secondary-dark)); width: {{ $quotaPct }}%; height: 100%; border-radius: 4px;"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Recent Registrants -->
        <div class="card" style="padding: 2rem;">
            <h3 class="card-title" style="color: var(--primary); margin-bottom: 1.5rem;"><i class="fa-solid fa-clock-rotate-left" style="margin-right: 0.5rem;"></i> Pendaftar Terbaru</h3>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jurusan</th>
                            <th>Tanggal Daftar</th>
                            <th>Status Seleksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrants as $recent)
                            <tr>
                                <td>{{ $recent->nisn }}</td>
                                <td><strong>{{ $recent->nama }}</strong></td>
                                <td>{{ $recent->jurusan }}</td>
                                <td>{{ \Carbon\Carbon::parse($recent->pendaftaran->tanggal_daftar)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $recent->pendaftaran->status }}">{{ $recent->pendaftaran->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('kepsek.students.show', $recent->id_siswa) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--gray-400); font-style: italic; padding: 2rem;">
                                    Belum ada data pendaftar masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
