@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="dashboard-container">
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link active">
                    <i class="fa-solid fa-money-bill-wave"></i> Verifikasi Pembayaran
                </a>
                <a href="{{ route('admin.export') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </a>
            </nav>
        </div>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header" style="margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Verifikasi Administrasi Pembayaran</h1>
                <p style="color: var(--gray-600);">Validasi bukti transfer biaya pendaftaran yang diunggah calon siswa.</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form action="{{ route('admin.payments.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div style="width: 200px;">
                    <label for="status" class="form-label" style="font-size: 0.8rem;">Status Verifikasi</label>
                    <select name="status" id="status" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">
                        <option value="">Semua</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Telah Valid</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Filter</button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Reset</a>
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
                        <th>Status</th>
                        <th>Aksi Verifikasi</th>
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
                                @php
                                    $sudahDibayar = $pendaftaran ? $pendaftaran->pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
                                    $sisaBayar = max(0, 3100000 - $sudahDibayar);
                                @endphp
                                <span style="display: block; font-size: 0.75rem; color: {{ $sisaBayar == 0 ? 'var(--success)' : 'var(--danger)' }}; font-weight: 500;">
                                    Sisa: {{ $sisaBayar == 0 ? 'Lunas' : 'Rp ' . number_format($sisaBayar, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $payment->bukti_bayar) }}" target="_blank" style="color: var(--secondary-dark); font-weight: 600;">
                                    <i class="fa-solid fa-image"></i> Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-{{ $payment->status }}">{{ $payment->status }}</span>
                            </td>
                            <td>
                                @if($payment->status === 'menunggu')
                                    <a href="{{ route('admin.payments.show', $payment->id_pembayaran) }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="fa-solid fa-file-invoice-dollar"></i> Verifikasi
                                    </a>
                                @else
                                    <a href="{{ route('admin.payments.show', $payment->id_pembayaran) }}" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.5rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada data bukti transfer pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        @if($payments->hasPages())
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                <span style="font-size: 0.85rem; color: var(--gray-600);">
                    Menampilkan {{ $payments->firstItem() }} s.d {{ $payments->lastItem() }} dari {{ $payments->total() }} transaksi
                </span>
                <div style="display: flex; gap: 0.5rem;">
                    @if($payments->onFirstPage())
                        <button class="btn btn-secondary btn-sm" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                    @endif

                    @if($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>Berikutnya</button>
                    @endif
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
