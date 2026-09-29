@extends('layouts.app')

@section('title', 'Detail Calon Siswa')

@section('content')
<div class="dashboard-container">
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

    <main class="dashboard-content" style="padding: 1.5rem 2rem;">
        <!-- Compact Header -->
        <div class="dashboard-header" style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.45rem; margin-bottom: 0.15rem; font-weight: 700;">Detail Pendaftar</h1>
                <p style="color: var(--gray-600); font-size: 0.85rem; margin: 0;">Detail data diri, dokumen pendukung, dan verifikasi kelulusan siswa.</p>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.85rem; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Balanced 2-Column Grid (Eliminates Long Scroll) -->
        <div style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 1.25rem; align-items: start;">
            
            <!-- Left Column: Profil & Berkas -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Profil Siswa Card -->
                <div class="detail-section" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-radius: var(--radius-sm); background: white; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid var(--gray-200); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        <h3 style="color: var(--primary); margin: 0; font-size: 1.05rem; font-weight: 700;">
                            <i class="fa-solid fa-user-graduate" style="margin-right: 0.4rem;"></i> Profil Calon Siswa
                        </h3>
                        <a href="{{ route('admin.students.edit', $siswa->id_siswa) }}" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.55rem; font-size: 0.75rem;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                    </div>

                    <!-- 2-Column Info Grid for Space Efficiency -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem 1.25rem; font-size: 0.825rem;">
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">No. Pendaftaran</span>
                            <strong style="color: var(--primary);">REG-{{ str_pad($pendaftaran->id_daftar, 4, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Jurusan Pilihan</span>
                            <strong style="color: var(--secondary-dark); font-size: 0.9rem;">{{ $siswa->jurusan }}</strong>
                        </div>

                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Nama Lengkap</span>
                            <strong>{{ $siswa->nama }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">NISN</span>
                            <span>{{ $siswa->nisn }}</span>
                        </div>

                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Tempat, Tanggal Lahir</span>
                            <span>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</span>
                        </div>
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Jenis Kelamin / Agama</span>
                            <span>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $siswa->agama ?? '-' }}</span>
                        </div>

                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">No HP / WhatsApp</span>
                            <span>{{ $siswa->no_hp }}</span>
                        </div>
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Email</span>
                            <span>{{ $siswa->email }}</span>
                        </div>

                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Tinggi / Penyakit Bawaan</span>
                            <span>{{ $siswa->tinggi_badan ? $siswa->tinggi_badan . ' cm' : '-' }} / {{ $siswa->penyakit ?? 'Tidak ada' }}</span>
                        </div>
                        <div>
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Anak Ke- / Jumlah Saudara</span>
                            <span>{{ $siswa->anak_ke ?? '-' }} dari {{ $siswa->jumlah_saudara ?? '0' }} bersaudara</span>
                        </div>

                        <div style="grid-column: 1 / -1;">
                            <span style="color: var(--gray-500); display: block; font-size: 0.725rem;">Alamat Lengkap</span>
                            <span style="line-height: 1.3;">
                                {{ $siswa->alamat }}
                                @if($siswa->kelurahan || $siswa->kecamatan)
                                    (Kel. {{ $siswa->kelurahan ?? '-' }}, Kec. {{ $siswa->kecamatan ?? '-' }}, {{ $siswa->kota ?? '-' }})
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Parents Info (Side-by-side in 2 compact columns) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px dashed var(--gray-200); font-size: 0.8rem;">
                        <div style="background: #f8fafc; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);">
                            <div style="font-weight: 700; color: var(--primary); margin-bottom: 0.35rem; font-size: 0.825rem;">
                                <i class="fa-solid fa-user-tie"></i> Data Ayah
                            </div>
                            <div>Nama: <strong>{{ $siswa->nama_ayah ?? '-' }}</strong></div>
                            <div>Pekerjaan: {{ $siswa->pekerjaan_ayah ?? '-' }}</div>
                            <div>Pendidikan: {{ $siswa->pendidikan_ayah ?? '-' }}</div>
                            <div>Penghasilan: {{ $siswa->penghasilan_ayah ?? '-' }}</div>
                        </div>

                        <div style="background: #f8fafc; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);">
                            <div style="font-weight: 700; color: var(--primary); margin-bottom: 0.35rem; font-size: 0.825rem;">
                                <i class="fa-solid fa-user-nurse"></i> Data Ibu
                            </div>
                            <div>Nama: <strong>{{ $siswa->nama_ibu ?? '-' }}</strong></div>
                            <div>Pekerjaan: {{ $siswa->pekerjaan_ibu ?? '-' }}</div>
                            <div>Pendidikan: {{ $siswa->pendidikan_ibu ?? '-' }}</div>
                            <div>No HP Ortu: {{ $siswa->no_hp_ortu ?? '-' }}</div>
                        </div>
                    </div>

                    @if($siswa->nama_wali)
                        <div style="margin-top: 0.75rem; background: #f8fafc; padding: 0.5rem 0.8rem; border-radius: var(--radius-sm); font-size: 0.775rem; border: 1px solid var(--gray-200);">
                            <strong style="color: var(--primary);"><i class="fa-solid fa-user-shield"></i> Data Wali:</strong> {{ $siswa->nama_wali }} ({{ $siswa->pekerjaan_wali ?? '-' }} - {{ $siswa->no_hp_wali ?? '-' }})
                        </div>
                    @endif
                </div>

                <!-- Berkas Persyaratan Card -->
                <div class="detail-section" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-radius: var(--radius-sm); background: white; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                        <i class="fa-solid fa-folder-open" style="color: #1e3a8a; font-size: 1.2rem;"></i>
                        <h3 style="color: #1e3a8a; font-size: 1.05rem; font-weight: 700; margin: 0;">Dokumen Persyaratan</h3>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                            <thead>
                                <tr style="border-bottom: 1.5px solid #f1f5f9; color: #475569; font-size: 0.825rem;">
                                    <th style="text-align: left; padding: 0.65rem 0.5rem; width: 45px; font-weight: 700;">No</th>
                                    <th style="text-align: left; padding: 0.65rem 0.5rem; font-weight: 700;">Jenis Dokumen</th>
                                    <th style="text-align: left; padding: 0.65rem 0.5rem; font-weight: 700;">Status</th>
                                    <th style="text-align: left; padding: 0.65rem 0.5rem; width: 90px; font-weight: 700;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $docs = [
                                        ['key' => 'ijazah', 'label' => 'Scan Ijazah / SKL'],
                                        ['key' => 'akta', 'label' => 'Scan Akta Kelahiran'],
                                        ['key' => 'kps', 'label' => 'Scan KPS (Opsional)'],
                                        ['key' => 'kk', 'label' => 'Scan KK'],
                                        ['key' => 'ktp orang tua', 'label' => 'Scan KTP Orang Tua'],
                                        ['key' => 'kip', 'label' => 'Scan KIP (Opsional)'],
                                    ];
                                @endphp

                                @foreach($docs as $index => $item)
                                    @php
                                        $key = $item['key'];
                                        $label = $item['label'];
                                        $isUploaded = isset($uploadedDocs[$key]);
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f8fafc;">
                                        <td style="padding: 0.75rem 0.5rem; color: #1e293b; font-weight: 700;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td style="padding: 0.75rem 0.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                                <div style="width: 32px; height: 32px; background: #e0f2fe; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fa-solid fa-file-lines" style="color: #0369a1; font-size: 0.85rem;"></i>
                                                </div>
                                                <span style="color: #334155; font-weight: 600; font-size: 0.85rem;">{{ $label }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 0.75rem 0.5rem;">
                                            @if($isUploaded)
                                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: #dcfce7; color: #15803d; padding: 0.3rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                                    <i class="fa-solid fa-circle-check" style="color: #16a34a; font-size: 0.85rem;"></i> Sudah Diunggah
                                                </span>
                                            @else
                                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: #fee2e2; color: #b91c1c; padding: 0.3rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                                    <i class="fa-solid fa-circle-exclamation" style="color: #dc2626; font-size: 0.85rem;"></i> Belum Diunggah
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 0.75rem 0.5rem;">
                                            @if($isUploaded)
                                                <a href="{{ asset('storage/' . $uploadedDocs[$key]) }}" target="_blank" style="display: inline-block; padding: 0.3rem 1.1rem; background: #ffffff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#93c5fd';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#bfdbfe';">
                                                    Lihat
                                                </a>
                                            @else
                                                <span style="color: #94a3b8; font-weight: 600; font-size: 0.9rem; padding-left: 0.6rem;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column: Keputusan & Pembayaran -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Keputusan Kelulusan Card -->
                <div class="detail-section" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-radius: var(--radius-sm); background: white; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                        <i class="fa-solid fa-clipboard-list" style="color: #1e3a8a; font-size: 1.2rem;"></i>
                        <h3 style="color: #1e3a8a; font-size: 1.05rem; font-weight: 700; margin: 0;">Status & Keputusan Pendaftaran</h3>
                    </div>

                    <!-- Top Info Box (Matching Mockup) -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
                        <!-- Left: Status Pendaftaran -->
                        <div style="border-right: 1px solid #f1f5f9; padding-right: 1rem;">
                            <span style="display: block; font-size: 0.775rem; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">Status Pendaftaran</span>
                            @if($pendaftaran->status === 'diterima')
                                <span style="display: inline-flex; align-items: center; gap: 0.45rem; background: #dcfce7; color: #15803d; padding: 0.35rem 0.9rem; border-radius: 8px; border: 1px solid #bbf7d0; font-size: 0.85rem; font-weight: 700;">
                                    <i class="fa-solid fa-circle-check" style="color: #16a34a; font-size: 0.95rem;"></i> Diterima
                                </span>
                            @elseif($pendaftaran->status === 'verifikasi')
                                <span style="display: inline-flex; align-items: center; gap: 0.45rem; background: #e0f2fe; color: #0369a1; padding: 0.35rem 0.9rem; border-radius: 8px; border: 1px solid #bae6fd; font-size: 0.85rem; font-weight: 700;">
                                    <i class="fa-solid fa-circle-info" style="color: #0284c7; font-size: 0.95rem;"></i> Verifikasi
                                </span>
                            @elseif($pendaftaran->status === 'ditolak')
                                <span style="display: inline-flex; align-items: center; gap: 0.45rem; background: #fee2e2; color: #b91c1c; padding: 0.35rem 0.9rem; border-radius: 8px; border: 1px solid #fecaca; font-size: 0.85rem; font-weight: 700;">
                                    <i class="fa-solid fa-circle-xmark" style="color: #dc2626; font-size: 0.95rem;"></i> Ditolak
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.45rem; background: #fef3c7; color: #b45309; padding: 0.35rem 0.9rem; border-radius: 8px; border: 1px solid #fde68a; font-size: 0.85rem; font-weight: 700;">
                                    <i class="fa-solid fa-clock" style="color: #d97706; font-size: 0.95rem;"></i> Menunggu
                                </span>
                            @endif
                        </div>

                        <!-- Right: Tanggal Pendaftaran -->
                        <div>
                            <span style="display: block; font-size: 0.775rem; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">Tanggal Pendaftaran</span>
                            @php
                                $months = [
                                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                ];
                                $tglDaftar = \Carbon\Carbon::parse($pendaftaran->tanggal_daftar ?? $pendaftaran->created_at);
                                $formattedTglDaftar = $tglDaftar->format('d') . ' ' . $months[$tglDaftar->month] . ' ' . $tglDaftar->format('Y, H:i');
                            @endphp
                            <strong style="color: #1e293b; font-size: 0.875rem; display: block; line-height: 1.4;">
                                {{ $formattedTglDaftar }}
                            </strong>
                        </div>
                    </div>

                    <!-- Bottom: Catatan / Alasan Keputusan Display -->
                    <div style="margin-bottom: 1.25rem;">
                        <span style="display: block; font-size: 0.8rem; color: #1e3a8a; font-weight: 700; margin-bottom: 0.5rem;">Catatan / Alasan Keputusan</span>
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.85rem 1.15rem; color: #475569; font-size: 0.85rem; min-height: 48px; line-height: 1.5;">
                            {{ $pendaftaran->keterangan ?: 'Belum ada catatan keputusan.' }}
                        </div>
                    </div>

                    <!-- Admin Update Form Action -->
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                        <form action="{{ route('admin.students.verify', $siswa->id_siswa) }}" method="POST">
                            @csrf
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="status" class="form-label" style="font-size: 0.775rem; font-weight: 600; color: #64748b;">Ubah Status</label>
                                    <select name="status" id="status" class="form-control" required style="padding: 0.4rem 0.6rem; font-size: 0.825rem; height: 36px; border-radius: 6px;">
                                        <option value="menunggu" {{ $pendaftaran->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="verifikasi" {{ $pendaftaran->status === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                        <option value="diterima" {{ $pendaftaran->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="ditolak" {{ $pendaftaran->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div style="display: flex; align-items: flex-end;">
                                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 36px; padding: 0.4rem 0.75rem; font-size: 0.825rem; display: inline-flex; justify-content: center; align-items: center; gap: 0.35rem; border-radius: 6px;">
                                        <i class="fa-solid fa-circle-check"></i> Simpan Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Status Pembayaran & Angsuran Card -->
                <div class="detail-section" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-radius: var(--radius-sm); background: white; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid var(--gray-200); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        <h3 style="color: var(--primary); margin: 0; font-size: 1.05rem; font-weight: 700;">
                            <i class="fa-solid fa-receipt" style="margin-right: 0.4rem;"></i> Status Pembayaran & Angsuran
                        </h3>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.55rem; font-size: 0.725rem;">
                            <i class="fa-solid fa-money-bill-wave"></i> Verifikasi Pembayaran
                        </a>
                    </div>
                    
                    @php
                        $totalBiaya = 3100000;
                        $sudahDibayar = $pembayaran ? $pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
                        $sisaBayar = max(0, $totalBiaya - $sudahDibayar);
                        $hasPending = $pembayaran ? $pembayaran->where('status', 'menunggu')->count() > 0 : false;
                        
                        $statusText = 'Belum Lunas';
                        $statusColor = 'var(--danger)';
                        $statusBg = '#fff5f5';
                        $statusBorder = '1px solid rgba(239, 68, 68, 0.2)';
                        
                        if ($sisaBayar == 0 && !$hasPending) {
                            $statusText = 'Lunas';
                            $statusColor = 'var(--success)';
                            $statusBg = 'rgba(16, 185, 129, 0.1)';
                            $statusBorder = '1.5px solid rgba(16, 185, 129, 0.2)';
                        } elseif ($hasPending) {
                            $statusText = 'Menunggu Verifikasi';
                            $statusColor = 'var(--info)';
                            $statusBg = 'rgba(59, 130, 246, 0.1)';
                            $statusBorder = '1.5px solid rgba(59, 130, 246, 0.2)';
                        } elseif ($sudahDibayar > 0) {
                            $statusText = 'Belum Lunas (Cicilan)';
                            $statusColor = 'var(--warning)';
                            $statusBg = 'rgba(245, 158, 11, 0.1)';
                            $statusBorder = '1.5px solid rgba(245, 158, 11, 0.2)';
                        }
                    @endphp

                    <!-- Compact Stats Grid (2x2) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-bottom: 1rem;">
                        <div style="border: 1px solid var(--gray-200); padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                            <span style="font-size: 0.7rem; color: var(--gray-600); font-weight: 600;">Total Tagihan</span>
                            <strong style="font-size: 0.95rem; color: var(--primary);">Rp 3.100.000</strong>
                        </div>
                        <div style="border: 1px solid var(--gray-200); padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                            <span style="font-size: 0.7rem; color: var(--gray-600); font-weight: 600;">Total Dibayar</span>
                            <strong style="font-size: 0.95rem; color: var(--success);">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</strong>
                        </div>
                        <div style="border: 1px solid var(--gray-200); padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                            <span style="font-size: 0.7rem; color: var(--gray-600); font-weight: 600;">Sisa Pembayaran</span>
                            <strong style="font-size: 0.95rem; color: var(--danger);">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</strong>
                        </div>
                        <div style="background: {{ $statusBg }}; border: {{ $statusBorder }}; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); display: flex; flex-direction: column; justify-content: center;">
                            <span style="font-size: 0.7rem; color: var(--gray-600); font-weight: 600;">Status</span>
                            <strong style="font-size: 0.85rem; color: {{ $statusColor }};">{{ $statusText }}</strong>
                        </div>
                    </div>

                    <!-- 4 Slot Angsuran in 2x2 Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                            @endphp
                            <div style="border: 1px solid var(--gray-200); border-radius: var(--radius-sm); padding: 0.6rem 0.75rem; background: var(--white);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <span style="font-weight: 700; font-size: 0.775rem; color: var(--gray-700);">Angsuran {{ $i }}</span>
                                    @if($pembayaranAngsuran)
                                        <span class="badge badge-{{ $pembayaranAngsuran->status }}" style="font-size: 0.6rem; padding: 0.15rem 0.4rem;">
                                            {{ $pembayaranAngsuran->status }}
                                        </span>
                                    @endif
                                </div>
                                @if($pembayaranAngsuran)
                                    <div style="font-weight: bold; font-size: 0.875rem; color: var(--primary);">Rp {{ number_format($pembayaranAngsuran->jumlah, 0, ',', '.') }}</div>
                                    <div style="font-size: 0.675rem; color: var(--gray-500);">
                                        Tgl: {{ \Carbon\Carbon::parse($pembayaranAngsuran->tanggal_bayar)->format('d-m-Y') }} ({{ ucfirst($pembayaranAngsuran->metode_pembayaran) }})
                                    </div>
                                    @if($pembayaranAngsuran->bukti_bayar)
                                        <a href="{{ asset('storage/' . $pembayaranAngsuran->bukti_bayar) }}" target="_blank" style="display: inline-block; font-size: 0.675rem; color: var(--secondary-dark); font-weight: 600; margin-top: 0.25rem; text-decoration: none;">Lihat Bukti</a>
                                    @endif
                                @else
                                    <div style="font-size: 0.725rem; color: var(--gray-400); font-style: italic; margin-top: 0.25rem;">Belum ada data</div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection
