@extends('layouts.app')

@section('title', 'Kelola Calon Siswa')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link active">
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
    </aside>

    <!-- Content -->
    <main class="dashboard-content" style="padding: 1.5rem 2rem;">
        <!-- Compact Header -->
        <div class="dashboard-header" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.45rem; margin-bottom: 0.15rem; font-weight: 700;">Data Calon Siswa</h1>
                <p style="color: var(--gray-600); font-size: 0.85rem; margin: 0;">Kelola profil, berkas, status administrasi, dan kelulusan pendaftar.</p>
            </div>
            <div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-accent btn-sm" style="padding: 0.45rem 0.9rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <i class="fa-solid fa-user-plus"></i> Tambah Calon Siswa
                </a>
            </div>
        </div>

        <!-- Sleek Inline Filter Toolbar -->
        <div class="card" style="padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: var(--radius-sm); background: white; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
            <form action="{{ route('admin.students.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: center;">
                <div style="flex: 1; min-width: 180px; position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 0.8rem;"></i>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari Nama / NISN / Email..." value="{{ request('search') }}" style="padding: 0.4rem 0.75rem 0.4rem 2.1rem; font-size: 0.825rem; height: 36px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); width: 100%;">
                </div>
                
                <div style="width: 145px;">
                    <select name="status_registrasi" id="status_registrasi" class="form-control" style="padding: 0.4rem 0.6rem; font-size: 0.825rem; height: 36px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); width: 100%;">
                        <option value="">Form: Semua</option>
                        <option value="sudah_form" {{ request('status_registrasi') == 'sudah_form' ? 'selected' : '' }}>Sudah Formulir</option>
                        <option value="registrasi" {{ request('status_registrasi') == 'registrasi' ? 'selected' : '' }}>Baru Akun</option>
                    </select>
                </div>

                <div style="width: 135px;">
                    <select name="jurusan" id="jurusan" class="form-control" style="padding: 0.4rem 0.6rem; font-size: 0.825rem; height: 36px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); width: 100%;">
                        <option value="">Jurusan: Semua</option>
                        <option value="Animasi" {{ request('jurusan') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                        <option value="Akuntansi" {{ request('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="Pemasaran" {{ request('jurusan') == 'Pemasaran' ? 'selected' : '' }}>Pemasaran</option>
                    </select>
                </div>

                <div style="width: 145px;">
                    <select name="status" id="status" class="form-control" style="padding: 0.4rem 0.6rem; font-size: 0.825rem; height: 36px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); width: 100%;">
                        <option value="">Seleksi: Semua</option>
                        <option value="perlu_verifikasi" {{ request('status') == 'perlu_verifikasi' ? 'selected' : '' }}>Perlu Verifikasi</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.4rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.85rem; font-size: 0.825rem; height: 36px; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status_registrasi', 'jurusan', 'status']))
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="padding: 0.4rem 0.85rem; font-size: 0.825rem; height: 36px; display: inline-flex; align-items: center;">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Student List Table (Compact & Space-Optimized) -->
        <div class="table-responsive" style="margin-bottom: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); background: white; box-shadow: var(--shadow-sm);">
            <table class="table" style="margin-bottom: 0; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1.5px solid var(--gray-200);">
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary);">Nama Calon Siswa</th>
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary); width: 130px;">NISN</th>
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary); width: 130px;">Jurusan</th>
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary); width: 150px; text-align: center;">Status Pendaftaran</th>
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary); width: 140px; text-align: center;">Status Pembayaran</th>
                        <th style="padding: 0.65rem 0.9rem; font-size: 0.825rem; font-weight: 700; color: var(--primary); width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $user)
                        @php
                            $student = $user->calonSiswa;
                            $pendaftaran = $student ? $student->pendaftaran : null;
                            $pembayaran = $pendaftaran ? $pendaftaran->pembayaran : null;
                            $totalBiaya = 3100000;
                            $sudahDibayar = $pembayaran ? $pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
                            $hasPending = $pembayaran ? $pembayaran->where('status', 'menunggu')->count() > 0 : false;
                            
                            if ($student) {
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
                            } else {
                                $statusLabel = '-';
                                $badgeClass = 'secondary';
                            }
                        @endphp
                        <tr style="border-bottom: 1px solid var(--gray-100);">
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle;">
                                <div style="font-weight: 600; color: var(--gray-800); font-size: 0.875rem; line-height: 1.3;">{{ $student ? $student->nama : $user->name }}</div>
                                <div style="font-size: 0.725rem; color: var(--gray-400);">{{ $user->email }}</div>
                            </td>
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle; font-size: 0.825rem; font-weight: 500; color: var(--gray-700);">
                                {{ $student ? $student->nisn : '-' }}
                            </td>
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle; font-size: 0.825rem;">
                                <span style="font-weight: 600; color: var(--primary);">{{ $student ? $student->jurusan : '-' }}</span>
                            </td>
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle; text-align: center;">
                                @if($student)
                                    @if($pendaftaran)
                                        <span class="badge badge-{{ $pendaftaran->status }}" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">{{ ucfirst($pendaftaran->status) }}</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">Belum Diproses</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary" style="background-color: #6c757d; color: white; font-size: 0.7rem; padding: 0.2rem 0.6rem;">Registrasi</span>
                                @endif
                            </td>
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle; text-align: center;">
                                @if($student)
                                    <span class="badge badge-{{ $badgeClass }}" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">{{ $statusLabel }}</span>
                                @else
                                    <span class="badge badge-secondary" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">-</span>
                                @endif
                            </td>
                            <td style="padding: 0.55rem 0.9rem; vertical-align: middle; text-align: center;">
                                <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: center;">
                                    @if($student)
                                        <a href="{{ route('admin.students.show', $student->id_siswa) }}" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                        <form action="{{ route('admin.students.delete', $student->id_siswa) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus calon siswa ini beserta seluruh berkas dan akun loginnnya?');" style="display: inline; margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.45rem; font-size: 0.75rem;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    @else
                                        <span style="font-size: 0.75rem; color: var(--gray-400); font-style: italic;">Akun</span>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun loginnnya?');" style="display: inline; margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.45rem; font-size: 0.75rem;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--gray-400);">Tidak ada data calon siswa ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Compact Pagination -->
        @if($students->hasPages())
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;">
                <span style="font-size: 0.8rem; color: var(--gray-600);">
                    Menampilkan {{ $students->firstItem() }} s.d {{ $students->lastItem() }} dari {{ $students->total() }} pendaftar
                </span>
                <div style="display: flex; gap: 0.4rem;">
                    @if($students->onFirstPage())
                        <button class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $students->previousPageUrl() }}" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;">Sebelumnya</a>
                    @endif

                    @if($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;">Berikutnya</a>
                    @else
                        <button class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;" disabled>Berikutnya</button>
                    @endif
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
