@extends('layouts.app')

@section('title', 'Detail Calon Siswa')

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
        <div class="dashboard-header" style="margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Detail Pendaftar</h1>
                <p style="color: var(--gray-600);">Detail data diri, dokumen pendukung, dan pembayaran siswa (Mode Baca).</p>
            </div>
            <div>
                <a href="{{ route('kepsek.students.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Profil Siswa Card -->
            <div class="card" style="padding: 2rem; box-shadow: var(--shadow-sm);">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid var(--gray-200); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--primary); margin: 0; font-size: 1.25rem;"><i class="fa-solid fa-user-graduate" style="margin-right: 0.5rem;"></i> Profil Calon Siswa</h3>
                    <span class="badge badge-{{ $pendaftaran->status }}" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                        Status: {{ ucfirst($pendaftaran->status) }}
                    </span>
                </div>
                <table class="info-table" style="margin-top: 0;">
                    <tr>
                        <td style="width: 30%;">No. Pendaftaran</td>
                        <td><strong>REG-{{ str_pad($pendaftaran->id_daftar, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td><strong>{{ $siswa->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td>NISN (Nomor Induk Siswa Nasional)</td>
                        <td>{{ $siswa->nisn }}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin / Agama</td>
                        <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $siswa->agama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kewarganegaraan</td>
                        <td>{{ $siswa->kewarganegaraan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tinggi / Penyakit Bawaan</td>
                        <td>{{ $siswa->tinggi_badan ? $siswa->tinggi_badan . ' cm' : '-' }} / {{ $siswa->penyakit ?? 'Tidak ada' }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Saudara</td>
                        <td>{{ $siswa->jumlah_saudara ?? '0' }} bersaudara</td>
                    </tr>
                    <tr>
                        <td>Anak Ke-</td>
                        <td>{{ $siswa->anak_ke ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pilihan Jurusan</td>
                        <td><strong style="color: var(--secondary-dark);">{{ $siswa->jurusan }}</strong></td>
                    </tr>

                    <tr>
                        <td>No HP / WhatsApp</td>
                        <td>{{ $siswa->no_hp }}</td>
                    </tr>
                    <tr>
                        <td>Email Calon Siswa</td>
                        <td>{{ $siswa->email }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Lengkap</td>
                        <td style="line-height: 1.5;">
                            {{ $siswa->alamat }}<br>
                            Kel. {{ $siswa->kelurahan ?? '-' }}, Kec. {{ $siswa->kecamatan ?? '-' }}<br>
                            {{ $siswa->kota ?? '-' }}, {{ $siswa->provinsi ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Terdaftar</td>
                        <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>

                <!-- Parents Info Grid -->
                <div class="form-grid-2" style="margin-top: 2rem; border-top: 1.5px solid var(--gray-200); padding-top: 1.5rem;">
                    <div>
                        <h4 style="color: var(--primary); margin-bottom: 0.5rem;"><i class="fa-solid fa-user-tie"></i> Detail Ayah</h4>
                        <table class="info-table" style="font-size: 0.85rem;">
                            <tr>
                                <td>Nama Ayah</td>
                                <td><strong>{{ $siswa->nama_ayah ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Pekerjaan</td>
                                <td>{{ $siswa->pekerjaan_ayah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <td>{{ $siswa->pendidikan_ayah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Penghasilan</td>
                                <td>{{ $siswa->penghasilan_ayah ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h4 style="color: var(--primary); margin-bottom: 0.5rem;"><i class="fa-solid fa-user-nurse"></i> Detail Ibu</h4>
                        <table class="info-table" style="font-size: 0.85rem;">
                            <tr>
                                <td>Nama Ibu</td>
                                <td><strong>{{ $siswa->nama_ibu ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Pekerjaan</td>
                                <td>{{ $siswa->pekerjaan_ibu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <td>{{ $siswa->pendidikan_ibu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Penghasilan</td>
                                <td>{{ $siswa->penghasilan_ibu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No HP Ortu</td>
                                <td>{{ $siswa->no_hp_ortu ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Wali Info -->
                <div style="margin-top: 1.5rem; border-top: 1px solid var(--gray-100); padding-top: 1rem;">
                    <h4 style="color: var(--primary); margin-bottom: 0.5rem;"><i class="fa-solid fa-user-shield"></i> Detail Wali (jika ada)</h4>
                    <table class="info-table" style="font-size: 0.85rem;">
                        <tr>
                            <td>Nama Wali</td>
                            <td><strong>{{ $siswa->nama_wali ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>{{ $siswa->pekerjaan_wali ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Wali</td>
                            <td>{{ $siswa->alamat_wali ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>No HP Wali</td>
                            <td>{{ $siswa->no_hp_wali ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Berkas Persyaratan Card -->
            <div class="card" style="padding: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-sm); background: white; border: 1px solid var(--gray-200);">
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

            <!-- Status Angsuran & Saldo Card -->
            <div class="card" style="padding: 2rem; box-shadow: var(--shadow-sm);">
                <h3 class="detail-section-title" style="border-bottom: 2px solid var(--gray-200); padding-bottom: 0.5rem; margin-bottom: 1.5rem;"><i class="fa-solid fa-receipt" style="margin-right: 0.5rem;"></i> Status Pembayaran & Angsuran</h3>
                
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

                <!-- Stats Grid -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
                    <div style="border: 1px solid var(--gray-200); padding: 0.75rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                        <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Total Tagihan</span>
                        <strong style="font-size: 1.1rem; color: var(--primary);">Rp 3.100.000</strong>
                    </div>
                    <div style="border: 1px solid var(--gray-200); padding: 0.75rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                        <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Total Dibayar</span>
                        <strong style="font-size: 1.1rem; color: var(--success);">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</strong>
                    </div>
                    <div style="border: 1px solid var(--gray-200); padding: 0.75rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                        <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Sisa Pembayaran</span>
                        <strong style="font-size: 1.1rem; color: var(--danger);">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</strong>
                    </div>
                    <div style="background: {{ $statusBg }}; border: {{ $statusBorder }}; padding: 0.75rem; border-radius: var(--radius-sm); display: flex; flex-direction: column; justify-content: center;">
                        <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Status</span>
                        <strong style="font-size: 0.95rem; color: {{ $statusColor }};">{{ $statusText }}</strong>
                    </div>
                </div>

                <!-- Installment Slots Display -->
                <h4 style="font-size: 1rem; font-weight: 700; color: var(--gray-800); margin-bottom: 1rem;">Status 4 Slot Angsuran</h4>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                        @endphp
                        <div style="border: 1px solid var(--gray-200); border-radius: var(--radius-sm); padding: 0.75rem; background: var(--white);">
                            <div style="font-weight: 700; font-size: 0.8rem; color: var(--gray-600); margin-bottom: 0.25rem;">Angsuran {{ $i }}</div>
                            @if($pembayaranAngsuran)
                                <div style="font-weight: bold; font-size: 0.95rem; color: var(--primary); margin-bottom: 0.25rem;">Rp {{ number_format($pembayaranAngsuran->jumlah, 0, ',', '.') }}</div>
                                <div style="margin-bottom: 0.25rem;">
                                    <span class="badge badge-{{ $pembayaranAngsuran->status }}" style="font-size: 0.6rem; padding: 0.15rem 0.4rem;">
                                        {{ $pembayaranAngsuran->status }}
                                    </span>
                                </div>
                                <div style="font-size: 0.65rem; color: var(--gray-500);">Tgl: {{ \Carbon\Carbon::parse($pembayaranAngsuran->tanggal_bayar)->format('d-m-Y') }}</div>
                                @if($pembayaranAngsuran->bukti_bayar)
                                    <a href="{{ asset('storage/' . $pembayaranAngsuran->bukti_bayar) }}" target="_blank" style="display: block; font-size: 0.7rem; color: var(--secondary-dark); font-weight: 600; margin-top: 0.5rem; text-decoration: none;">Lihat Bukti</a>
                                @endif
                            @else
                                <div style="font-size: 0.75rem; color: var(--gray-400); font-style: italic;">Belum ada data</div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
