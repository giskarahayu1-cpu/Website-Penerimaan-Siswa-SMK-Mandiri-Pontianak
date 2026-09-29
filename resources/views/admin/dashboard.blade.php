@extends('layouts.app')

@section('title', 'Dashboard Panitia PPDB')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-money-bill-wave"></i> Verifikasi Pembayaran
                </a>
                <a href="{{ route('admin.export') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </a>
            </nav>
        </div>
        
        <div style="margin-top: auto;">
            <p style="font-size: 0.8rem; color: var(--gray-400); text-align: center;">
                Panitia PPDB v1.0<br>SMK Mandiri Pontianak
            </p>
        </div>
    </aside>

    <!-- Content -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Ringkasan Data</h1>
                <p style="color: var(--gray-600);">Monitor berkas, pembayaran, dan perkembangan kuota PPDB secara real-time.</p>
            </div>
            <div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-accent btn-sm">
                    <i class="fa-solid fa-user-plus"></i> Tambah Calon Siswa
                </a>
            </div>
        </div>

        <!-- Config Section Grid -->
        <div class="grid grid-2" style="margin-bottom: 2rem; gap: 2rem; align-items: stretch;">
            <!-- Period Toggle Card -->
            <div class="card" style="padding: 1.25rem 2rem; border-left: 5px solid {{ $pendaftaranStatus === 'dibuka' ? 'var(--success)' : 'var(--danger)' }}; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: center; margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="fa-solid fa-calendar-check" style="font-size: 1.5rem; color: {{ $pendaftaranStatus === 'dibuka' ? 'var(--success)' : 'var(--danger)' }};"></i>
                        <div>
                            <h4 style="margin: 0; color: var(--gray-800);">
                                Status Periode: 
                                <span class="badge badge-{{ $pendaftaranStatus === 'dibuka' ? 'verifikasi' : 'ditolak' }}" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; margin-left: 0.25rem;">
                                    {{ $pendaftaranStatus === 'dibuka' ? 'DIBUKA' : 'DITUTUP' }}
                                </span>
                            </h4>
                            <p style="color: var(--gray-600); font-size: 0.8rem; margin-top: 0.25rem; margin-bottom: 0;">
                                {{ $pendaftaranStatus === 'dibuka' ? 'Pendaftaran online dibuka.' : 'Pendaftaran online ditutup.' }}
                            </p>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.classes.toggle-period') }}" method="POST" style="margin: 0;">
                        @csrf
                        @if($pendaftaranStatus === 'dibuka')
                            <input type="hidden" name="status" value="ditutup">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menutup periode pendaftaran?')" style="display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fa-solid fa-lock"></i> Tutup
                            </button>
                        @else
                            <input type="hidden" name="status" value="dibuka">
                            <button type="submit" class="btn btn-success btn-sm" style="display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fa-solid fa-lock-open"></i> Buka
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Bank Transfer Settings Card -->
            <div class="card" style="padding: 1.25rem 2rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: center; margin-bottom: 0;">
                <h4 style="margin: 0 0 1rem 0; color: var(--gray-800); display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
                    <i class="fa-solid fa-building-columns" style="color: var(--primary);"></i>
                    Rekening Transfer Bank
                </h4>
                <form action="{{ route('admin.settings.update-bank') }}" method="POST" style="margin: 0;">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; align-items: end;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="bank_name" class="form-label" style="font-weight: 600; font-size: 0.75rem; color: var(--gray-700); margin-bottom: 0.25rem; display: block;">Nama Bank</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ \App\Models\Setting::get('bank_name', 'Bank Kalbar (BPD)') }}" required style="font-size: 0.8rem; padding: 0.4rem 0.75rem; height: auto;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="bank_account_number" class="form-label" style="font-weight: 600; font-size: 0.75rem; color: var(--gray-700); margin-bottom: 0.25rem; display: block;">No. Rekening</label>
                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" value="{{ \App\Models\Setting::get('bank_account_number', '101-23456-7890') }}" required style="font-size: 0.8rem; padding: 0.4rem 0.75rem; height: auto;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="bank_account_name" class="form-label" style="font-weight: 600; font-size: 0.75rem; color: var(--gray-700); margin-bottom: 0.25rem; display: block;">Atas Nama (A/N)</label>
                            <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" value="{{ \App\Models\Setting::get('bank_account_name', 'SMK MANDIRI PONTIANAK') }}" required style="font-size: 0.8rem; padding: 0.4rem 0.75rem; height: auto;">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 0.35rem; font-weight: 600; padding: 0.4rem 1rem;">
                            <i class="fa-solid fa-save"></i> Simpan Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="stats-grid">
            <a href="{{ route('admin.students.index', ['status_registrasi' => 'sudah_form']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $totalPendaftar }}</div>
                    <div class="stat-label">Total Pendaftar</div>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            </a>

            <a href="{{ route('admin.students.index', ['status_registrasi' => 'registrasi']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $totalBaruDaftarAkun }}</div>
                    <div class="stat-label">Baru Daftar Akun</div>
                </div>
                <div class="stat-icon" style="color: var(--info);"><i class="fa-solid fa-user-plus"></i></div>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'perlu_verifikasi']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['menunggu'] + $statuses['verifikasi'] }}</div>
                    <div class="stat-label">Perlu Verifikasi</div>
                </div>
                <div class="stat-icon" style="color: var(--warning);"><i class="fa-solid fa-user-clock"></i></div>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'diterima']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['diterima'] }}</div>
                    <div class="stat-label">Diterima</div>
                </div>
                <div class="stat-icon" style="color: var(--success);"><i class="fa-solid fa-user-check"></i></div>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'ditolak']) }}" class="stat-card">
                <div>
                    <div class="stat-num">{{ $statuses['ditolak'] }}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
                <div class="stat-icon" style="color: var(--danger);"><i class="fa-solid fa-user-xmark"></i></div>
            </a>
        </div>

        <div class="grid grid-2" style="margin-bottom: 2.5rem; align-items: start;">
            <!-- CSS Chart of Applicants per Year -->
            <div class="card">
                <h3 class="card-title" style="color: var(--primary);"><i class="fa-solid fa-chart-column" style="margin-right: 0.5rem;"></i> Grafik Pendaftar PPDB (Per Tahun)</h3>
                <p class="card-text" style="font-size: 0.85rem; margin-bottom: 1rem;">Perbandingan tren jumlah pendaftar tahun 2022 hingga tahun ajaran aktif 2026.</p>
                
                <div class="chart-container">
                    @foreach($chartData as $year => $value)
                        @php
                            // Calculate percentage height relative to max value (e.g. 200)
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
                                    <span class="badge badge-{{ $recent->pendaftaran->status }}">
                                        {{ $recent->pendaftaran->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.show', $recent->id_siswa) }}" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.5rem;">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">Belum ada pendaftar terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
