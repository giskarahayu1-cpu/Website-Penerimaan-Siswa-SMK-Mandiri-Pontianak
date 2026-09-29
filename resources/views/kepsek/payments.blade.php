@extends('layouts.app')

@section('title', 'Data Pembayaran PPDB')

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
                <a href="{{ route('kepsek.students.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('kepsek.payments.index') }}" class="sidebar-link active">
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

    <main class="dashboard-content">
        <div class="dashboard-header" style="margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Laporan Pembayaran Calon Siswa</h1>
                <p style="color: var(--gray-600);">Monitoring bukti transfer biaya pendaftaran yang diunggah calon siswa.</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form action="{{ route('kepsek.payments.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div style="width: 200px;">
                    <label for="status" class="form-label" style="font-size: 0.85rem;">Status Transaksi</label>
                    <select name="status" id="status" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.85rem; background: white;">
                        <option value="">Semua</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Telah Valid</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Filter</button>
                    <a href="{{ route('kepsek.payments.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Daftar</th>
                        <th>Nama Calon Siswa</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Bukti Transfer</th>
                        <th>Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $pendaftaran = $payment->pendaftaran;
                            $siswa = $pendaftaran ? $pendaftaran->calonSiswa : null;
                        @endphp
                        <tr>
                            <td>
                                @if($pendaftaran)
                                    <strong>REG-{{ str_pad($pendaftaran->id_daftar, 4, '0', STR_PAD_LEFT) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($siswa)
                                    <strong>{{ $siswa->nama }}</strong>
                                    <span style="display: block; font-size: 0.75rem; color: var(--gray-400);">NISN: {{ $siswa->nisn }} | {{ $siswa->jurusan }}</span>
                                @else
                                    <em style="color: var(--gray-400);">Unknown</em>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d-m-Y') }}</td>
                            <td>
                                <strong>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $payment->bukti_bayar) }}" target="_blank" style="color: var(--secondary-dark); font-weight: 600; text-decoration: none;">
                                    <i class="fa-solid fa-image"></i> Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-{{ $payment->status }}">{{ $payment->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray-400); font-style: italic; padding: 2rem;">
                                Tidak ada data transaksi pembayaran yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $payments->links() }}
        </div>
    </main>
</div>
@endsection
