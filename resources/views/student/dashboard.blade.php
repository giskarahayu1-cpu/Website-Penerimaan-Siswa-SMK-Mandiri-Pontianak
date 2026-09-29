@extends('layouts.app')

@section('title', 'Dashboard Calon Siswa')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <!-- Sidebar Header -->
            <div class="student-sidebar-header" style="margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.15rem; margin: 0; font-weight: 700; color: white;">PPDB SMK</h3>
                <p style="font-size: 0.75rem; color: var(--gray-400); margin: 0;">Tahun Ajaran 2025/2026</p>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('student.dashboard', ['tab' => 'pendaftaran']) }}" class="sidebar-link {{ $tab === 'pendaftaran' ? 'active' : '' }}">
                    <i class="fa-solid fa-file-signature"></i> Pendaftaran
                </a>
                @if($isLengkap)
                    <div class="sidebar-dropdown-group {{ $tab === 'administrasi' ? 'active' : '' }}">
                        <a href="{{ route('student.dashboard', ['tab' => 'administrasi']) }}" 
                           id="administrasi-toggle-btn"
                           class="sidebar-link {{ $tab === 'administrasi' ? 'active' : '' }}" 
                           style="display: flex; justify-content: space-between; align-items: center; width: 100%; cursor: pointer;"
                           onclick="handleAdministrasiClick(event)">
                            <span style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fa-solid fa-receipt"></i> Administrasi
                            </span>
                            <i id="administrasi-chevron" class="fa-solid fa-chevron-down dropdown-icon {{ $tab === 'administrasi' ? 'rotated' : '' }}" style="font-size: 0.8rem;"></i>
                        </a>
                        
                        <ul class="sidebar-submenu {{ $tab === 'administrasi' ? 'expanded' : 'collapsed' }}" id="administrasi-submenu">
                            <li class="{{ $tab === 'administrasi' ? 'active' : '' }}" id="submenu-rincian">
                                <span class="submenu-dot"></span>
                                @if($tab === 'administrasi')
                                    <a href="javascript:void(0)" onclick="switchSubtab('rincian')" class="submenu-link">Rincian Biaya Masuk</a>
                                @else
                                    <a href="{{ route('student.dashboard', ['tab' => 'administrasi']) }}#subtab-rincian" class="submenu-link">Rincian Biaya Masuk</a>
                                @endif
                            </li>
                            <li id="submenu-informasi">
                                <span class="submenu-dot"></span>
                                @if($tab === 'administrasi')
                                    <a href="javascript:void(0)" onclick="switchSubtab('informasi')" class="submenu-link">Informasi Pembayaran</a>
                                @else
                                    <a href="{{ route('student.dashboard', ['tab' => 'administrasi']) }}#subtab-informasi" class="submenu-link">Informasi Pembayaran</a>
                                @endif
                            </li>
                            <li id="submenu-verifikasi">
                                <span class="submenu-dot"></span>
                                @if($tab === 'administrasi')
                                    <a href="javascript:void(0)" onclick="switchSubtab('verifikasi')" class="submenu-link">Verifikasi Pembayaran</a>
                                @else
                                    <a href="{{ route('student.dashboard', ['tab' => 'administrasi']) }}#subtab-verifikasi" class="submenu-link">Verifikasi Pembayaran</a>
                                @endif
                            </li>
                            <li id="submenu-laporan">
                                <span class="submenu-dot"></span>
                                @if($tab === 'administrasi')
                                    <a href="javascript:void(0)" onclick="switchSubtab('laporan')" class="submenu-link">Laporan Pembayaran</a>
                                @else
                                    <a href="{{ route('student.dashboard', ['tab' => 'administrasi']) }}#subtab-laporan" class="submenu-link">Laporan Pembayaran</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                @else
                    @php
                        $alertMsg = 'Anda belum dapat mengakses menu Administrasi. Silakan lengkapi ';
                        $msgParts = [];
                        if (!$isDataDiriLengkap) {
                            $msgParts[] = 'data diri Anda';
                        }
                        if (!$isBerkasLengkap) {
                            $msgParts[] = 'semua berkas persyaratan wajib (' . implode(', ', $missingDocs) . ')';
                        }
                        $alertMsg .= implode(' dan ', $msgParts) . ' terlebih dahulu.';
                    @endphp
                    <a href="javascript:void(0)" class="sidebar-link" onclick="alert('{{ $alertMsg }}')" style="opacity: 0.6; cursor: not-allowed;" title="Lengkapi data diri dan berkas untuk membuka menu ini">
                        <i class="fa-solid fa-lock"></i> Administrasi (Terkunci)
                    </a>
                @endif
                <a href="{{ route('student.dashboard', ['tab' => 'pengumuman']) }}" class="sidebar-link {{ $tab === 'pengumuman' ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i> Pengumuman
                </a>
            </nav>
        </div>

        <!-- User Widget -->
        <div class="sidebar-user-widget" style="display: flex; flex-direction: column; gap: 0.5rem; align-items: stretch; margin-top: auto; padding: 0.75rem; background: rgba(255, 255, 255, 0.05); border-radius: var(--radius-sm); border: 1px solid rgba(255, 255, 255, 0.1);">
            <div style="display: flex; align-items: center; gap: 0.65rem; width: 100%;">
                <div class="sidebar-user-avatar" style="width: 32px; height: 32px; font-size: 0.85rem;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="sidebar-user-info" style="overflow: hidden;">
                    <span class="sidebar-user-name" style="font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">{{ $siswa->nama }}</span>
                    <span class="sidebar-user-role" style="font-size: 0.7rem; color: #10b981; display: flex; align-items: center; gap: 4px;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Siswa
                    </span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger btn-block btn-sm" style="display: flex; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.35rem 0.5rem; font-size: 0.75rem; width: 100%; border-radius: var(--radius-sm);">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="dashboard-content">
        @if($tab === 'pendaftaran')
            <div class="dashboard-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--primary); font-size: 1.75rem;">Data Diri Pendaftar</h1>
                    <p style="color: var(--gray-600);">Berikut adalah rincian data pendaftaran yang telah disimpan di dalam sistem.</p>
                </div>
                @if(!($pendaftaran && $pendaftaran->status === 'diterima'))
                <div>
                    <a href="{{ route('student.edit') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-user-pen"></i> Edit Data Diri
                    </a>
                </div>
                @endif
            </div>

            <!-- Warning Alert if Incomplete -->
            @if(!$isLengkap)
                <div class="alert alert-danger" style="margin-bottom: 2rem; padding: 1.25rem; border-radius: var(--radius-sm); border-left: 5px solid var(--danger); background: #fff5f5;">
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem; color: var(--danger); margin-top: 0.1rem;"></i>
                        <div>
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--danger); font-weight: 700; font-size: 1.05rem;">Data Diri & Berkas Wajib Belum Lengkap!</h4>
                            <p style="margin: 0; font-size: 0.9rem; color: var(--gray-700); line-height: 1.5;">
                                Anda belum dapat mengakses menu <strong>Administrasi</strong>. Harap lengkapi hal-hal berikut:
                                <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0; font-size: 0.9rem; color: var(--gray-700); list-style-type: disc;">
                                    @if(!$isDataDiriLengkap)
                                        <li>Isi seluruh data profil diri Anda dengan lengkap (klik tombol <strong>Edit Data Diri</strong> di kanan atas).</li>
                                    @endif
                                    @if(!$isBerkasLengkap)
                                        <li>Unggah berkas persyaratan wajib yang masih kosong: <strong>{{ implode(', ', $missingDocs) }}</strong> (lakukan pada bagian bawah halaman ini).</li>
                                    @endif
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Info Grid -->
            <div class="card" style="padding: 2.5rem; margin-bottom: 2rem; border-radius: var(--radius-md);">
                <!-- Section: Data Pribadi -->
                <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user" style="color: var(--secondary-dark);"></i> Data Pribadi</h3>
                <table class="info-table">
                    <tr>
                        <td style="width: 30%;">Nama Lengkap</td>
                        <td><strong>{{ $siswa->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Siswa Nasional (NISN)</td>
                        <td><strong>{{ $siswa->nisn }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>{{ $siswa->agama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kewarganegaraan</td>
                        <td>{{ $siswa->kewarganegaraan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tinggi Badan / Penyakit Bawaan</td>
                        <td>{{ $siswa->tinggi_badan ? $siswa->tinggi_badan . ' cm' : '-' }} / {{ $siswa->penyakit ?? 'Tidak ada' }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Saudara Kandung</td>
                        <td>{{ $siswa->jumlah_saudara ?? '0' }} bersaudara</td>
                    </tr>
                    <tr>
                        <td>Anak Ke-</td>
                        <td>{{ $siswa->anak_ke ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. Handphone / WhatsApp</td>
                        <td>{{ $siswa->no_hp }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Tinggal</td>
                        <td>
                            {{ $siswa->alamat }}<br>
                            Kel. {{ $siswa->kelurahan ?? '-' }}, Kec. {{ $siswa->kecamatan ?? '-' }}<br>
                            {{ $siswa->kota ?? '-' }}, {{ $siswa->provinsi ?? '-' }}
                        </td>
                    </tr>
                </table>

                <!-- Section: Data Ayah & Ibu -->
                <div class="form-grid-2" style="margin-top: 2rem;">
                    <div>
                        <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user-tie" style="color: var(--secondary-dark);"></i> Data Ayah</h3>
                        <table class="info-table">
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
                        <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user-nurse" style="color: var(--secondary-dark);"></i> Data Ibu</h3>
                        <table class="info-table">
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

                <!-- Section: Data Wali -->
                <h3 class="form-section-title"><i class="fa-solid fa-user-shield" style="color: var(--secondary-dark);"></i> Data Wali (jika ada)</h3>
                <table class="info-table">
                    <tr>
                        <td style="width: 30%;">Nama Wali</td>
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

                <!-- Section: Pilihan Jurusan -->
                <h3 class="form-section-title"><i class="fa-solid fa-graduation-cap" style="color: var(--secondary-dark);"></i> Program Keahlian</h3>
                <table class="info-table">
                    <tr>
                        <td style="width: 30%;">Program Studi Keahlian</td>
                        <td><strong style="color: var(--primary-light); font-size: 1.1rem;">{{ $siswa->jurusan }}</strong></td>
                    </tr>
                </table>

                <!-- Section: Upload Berkas Persyaratan -->
                <h3 class="form-section-title"><i class="fa-solid fa-folder-open" style="color: var(--secondary-dark);"></i> Dokumen / Berkas Persyaratan</h3>
                <div class="doc-upload-grid" style="margin-top: 1rem;">
                    @php
                        $docs = [
                            'ijazah' => 'Scan Ijazah / SKL',
                            'kk' => 'Scan Kartu Keluarga (KK)',
                            'akta' => 'Scan Akta Kelahiran',
                            'ktp orang tua' => 'Scan KTP Orang Tua',
                            'kps' => 'Scan KPS (Kartu Perlindungan Sosial) (jika ada)',
                            'kip' => 'Scan KIP (Kartu Indonesia Pintar) (jika ada)'
                        ];
                    @endphp

                    @foreach($docs as $key => $label)
                        @php
                            $isUploaded = isset($uploadedDocs[$key]);
                        @endphp
                        <div class="doc-card {{ $isUploaded ? 'uploaded' : '' }}" style="padding: 1.25rem;">
                            <div class="doc-icon" style="font-size: 1.75rem; margin-bottom: 0.5rem;">
                                @if($isUploaded)
                                    <i class="fa-solid fa-circle-check" style="color: var(--success);"></i>
                                @else
                                    <i class="fa-solid fa-cloud-arrow-up" style="color: var(--gray-400);"></i>
                                @endif
                            </div>
                            <h4 class="doc-title" style="font-size: 0.85rem; margin-bottom: 0.15rem;">
                                {{ $label }}
                                @if($key !== 'kps' && $key !== 'kip')
                                    <span style="color: red;">*</span>
                                @endif
                            </h4>
                            <span style="font-size: 0.7rem; color: var(--gray-400); display: block; margin-bottom: 0.35rem;">Format: PDF, PNG, JPG (Maks. 2MB)</span>
                            <p class="doc-status" style="font-size: 0.75rem; margin-bottom: 0.75rem;">
                                Status: {!! $isUploaded ? '<strong style="color: var(--success);">Telah Diupload</strong>' : '<em style="color: var(--gray-400);">Belum Diupload</em>' !!}
                            </p>

                            @if($isUploaded)
                                <div style="display: flex; gap: 0.25rem; width: 100%;">
                                    <a href="{{ asset('storage/' . $uploadedDocs[$key]) }}" target="_blank" class="btn btn-secondary btn-sm" style="flex: 1; font-size: 0.7rem; padding: 0.3rem;">Lihat</a>
                                    <button type="button" class="btn btn-primary btn-sm" style="flex: 1; font-size: 0.7rem; padding: 0.3rem;" onclick="toggleUploadForm('{{ $key }}')">Ubah</button>
                                </div>
                            @else
                                <button type="button" class="btn btn-primary btn-sm btn-block" style="font-size: 0.7rem; padding: 0.35rem;" onclick="toggleUploadForm('{{ $key }}')">Upload File</button>
                            @endif

                            <!-- Hidden Upload Form Toggle -->
                            <form action="{{ route('student.upload-berkas') }}" method="POST" enctype="multipart/form-data" id="form-upload-{{ str_replace(' ', '-', $key) }}" style="display: none; margin-top: 0.75rem; width: 100%;">
                                @csrf
                                <input type="hidden" name="berkas_type" value="{{ $key }}">
                                <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                                    <input type="file" name="file_dokumen" class="form-control" style="font-size: 0.75rem; padding: 0.25rem;" required accept=".jpg,.jpeg,.png,.pdf">
                                    <span style="font-size: 0.65rem; color: var(--gray-400); text-align: left; margin-bottom: 0.2rem;">Format: PDF, PNG, JPG (Maks. 2MB)</span>
                                    <div style="display: flex; gap: 0.25rem;">
                                        <button type="submit" class="btn btn-accent btn-sm" style="flex: 1; font-size: 0.7rem; padding: 0.25rem;">Kirim</button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="flex: 1; font-size: 0.7rem; padding: 0.25rem;" onclick="toggleUploadForm('{{ $key }}')">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        @elseif($tab === 'administrasi')
            <div class="dashboard-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--primary); font-size: 1.75rem;">Pembayaran Administrasi</h1>
                    <p style="color: var(--gray-600);">Rincian tagihan biaya masuk sekolah dan unggah bukti transfer.</p>
                </div>
            </div>

            <!-- Subtab: Rincian Biaya Masuk -->
            <div id="subtab-rincian" class="subtab-content">
                <div class="card" style="padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
                    <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid var(--gray-100); padding-bottom: 0.75rem;">
                        <i class="fa-solid fa-receipt"></i> Rincian Biaya Masuk Sekolah
                    </h3>
                    <div class="table-responsive" style="border: 1px solid var(--gray-200); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 0;">
                        <table class="table" style="margin: 0; width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: var(--gray-100);">
                                    <th style="padding: 0.75rem 1rem; width: 8%; text-align: center; font-weight: 600; color: var(--gray-800); border-bottom: 1.5px solid var(--gray-200);">No</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: var(--gray-800); border-bottom: 1.5px solid var(--gray-200);">Deskripsi Kebutuhan</th>
                                    <th style="padding: 0.75rem 1rem; width: 25%; text-align: right; font-weight: 600; color: var(--gray-800); border-bottom: 1.5px solid var(--gray-200);">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">1</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">SPP 2 Bulan @ Rp 200.000 (Bulan Juli & Agustus)</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 400.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">2</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Sumbangan Pembangunan</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 800.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">3</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Seragam Olahraga (1 Stel)</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 300.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">4</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Seragam Praktek / Rumpun (1 Stel)</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 300.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">5</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">PLS (Pengenalan Lingkungan Sekolah)</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 50.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">6</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Iuran OSIS (Selama 1 Tahun)</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 450.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">7</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Biaya Pemeliharaan & Perbaikan Komputer</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 300.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">8</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Biaya Cetak Raport & Kartu Pelajar</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 200.000</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">9</td>
                                    <td style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200);">Biaya Administrasi</td>
                                    <td style="text-align: right; padding: 0.75rem 1rem; font-weight: 600; border-bottom: 1px solid var(--gray-200);">Rp 300.000</td>
                                </tr>
                                <tr style="background: var(--gray-100); font-weight: 700;">
                                    <td colspan="2" style="text-align: right; padding: 1rem; font-size: 1rem; color: var(--primary);">Total Tagihan PPDB:</td>
                                    <td style="text-align: right; padding: 1rem; font-size: 1.15rem; color: var(--primary);">Rp 3.100.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ketentuan Angsuran Pembayaran -->
                    <div style="margin-top: 1.25rem; padding: 1.1rem 1.25rem; background: rgba(0, 114, 255, 0.06); border-left: 4px solid var(--primary); border-radius: var(--radius-sm); display: flex; align-items: flex-start; gap: 0.85rem; box-shadow: var(--shadow-sm);">
                        <i class="fa-solid fa-circle-info" style="color: var(--primary); font-size: 1.2rem; margin-top: 0.15rem; flex-shrink: 0;"></i>
                        <p style="margin: 0; font-size: 0.92rem; color: var(--gray-800); line-height: 1.6;">
                            Rincian tersebut di atas diberi jangka waktu pembayaran dengan cicilan 4x angsuran, selama 3 bulan setelah proses belajar mengajar. Untuk cicilan pertama paling kurang Rp 1.500.000,- dari biaya yang telah ditetapkan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subtab: Informasi Pembayaran -->
            <div id="subtab-informasi" class="subtab-content" style="display: none;">
                <div class="card" style="padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
                    <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid var(--gray-100); padding-bottom: 0.75rem;">
                        <i class="fa-solid fa-wallet"></i> Metode Pembayaran & Rekening
                    </h3>
                    <p style="margin-bottom: 1.25rem; font-size: 0.9rem; color: var(--gray-600); line-height: 1.5;">
                        Pembayaran dapat dicicil (maksimal 4 kali) atau dilunasi sekaligus melalui dua metode berikut:
                    </p>
                    
                    <!-- Metode 1: Transfer Bank -->
                    <div style="margin-bottom: 1.5rem; background: var(--gray-50); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);">
                        <h5 style="margin: 0 0 0.75rem 0; font-size: 0.95rem; color: var(--gray-800); font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-building-columns" style="color: var(--primary);"></i> 1. Transfer Bank
                        </h5>
                        <table class="info-table" style="margin-top: 0; font-size: 0.85rem; width: 100%; background: transparent;">
                            <tr style="border-bottom: 1px solid var(--gray-200);">
                                <td style="padding: 0.5rem 0; color: var(--gray-600); border: none;">Bank Penerima</td>
                                <td style="padding: 0.5rem 0; text-align: right; border: none;"><strong>{{ \App\Models\Setting::get('bank_name', 'Bank Kalbar (BPD)') }}</strong></td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--gray-200);">
                                <td style="padding: 0.5rem 0; color: var(--gray-600); border: none;">Nomor Rekening</td>
                                <td style="padding: 0.5rem 0; text-align: right; color: var(--secondary-dark); border: none;"><strong>{{ \App\Models\Setting::get('bank_account_number', '101-23456-7890') }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0; color: var(--gray-600); border: none;">Atas Nama (A/N)</td>
                                <td style="padding: 0.5rem 0; text-align: right; border: none;"><strong>{{ \App\Models\Setting::get('bank_account_name', 'SMK MANDIRI PONTIANAK') }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Metode 2: Tunai / Cash -->
                    <div style="background: var(--gray-50); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200);">
                        <h5 style="margin: 0 0 0.5rem 0; font-size: 0.95rem; color: var(--gray-800); font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-money-bill-1-wave" style="color: var(--primary);"></i> 2. Tunai / Cash di Sekolah
                        </h5>
                        <p style="margin: 0; font-size: 0.85rem; color: var(--gray-600); line-height: 1.6;">
                            Pembayaran tunai langsung di loket pendaftaran/Tata Usaha SMK Mandiri Pontianak. 
                            <strong style="color: var(--danger); display: block; margin-top: 0.5rem;">
                                <i class="fa-solid fa-triangle-exclamation"></i> KETENTUAN PENTING:
                            </strong>
                            Setelah melakukan pembayaran secara tunai, harap meminta <strong>kwitansi resmi</strong> dari petugas dan unggah foto kwitansi tersebut sebagai bukti pembayaran.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subtab: Verifikasi Pembayaran -->
            <div id="subtab-verifikasi" class="subtab-content" style="display: none;">
                <!-- Stats Grid for Remaining payment information -->
                @php
                    $totalBiaya = 3100000;
                    $sudahDibayar = $pembayaran ? $pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
                    $sisaBayar = max(0, $totalBiaya - $sudahDibayar);
                    $hasPending = $pembayaran ? $pembayaran->where('status', 'menunggu')->count() > 0 : false;
                    
                    // Set Status text and state classes
                    $statusText = 'Belum Lunas';
                    $statusColor = 'var(--danger)';
                    $statusBg = '#fff5f5';
                    $statusBorder = '1.5px solid rgba(239, 68, 68, 0.2)';
                    
                    if ($sisaBayar == 0) {
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

                <div class="grid grid-4" style="gap: 1.25rem; margin-bottom: 2rem;">
                    <!-- Card 1: Total Tagihan -->
                    <div class="card" style="padding: 1.25rem; border-radius: var(--radius-sm); border-top: 4px solid var(--primary); display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; box-shadow: var(--shadow-sm);">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600);">Total Tagihan</span>
                        <strong style="font-size: 1.25rem; color: var(--primary);">Rp 3.100.000</strong>
                    </div>

                    <!-- Card 2: Total Dibayar -->
                    <div class="card" style="padding: 1.25rem; border-radius: var(--radius-sm); border-top: 4px solid var(--success); display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; box-shadow: var(--shadow-sm);">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600);">Total Dibayar</span>
                        <strong style="font-size: 1.25rem; color: var(--success);">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Card 3: Sisa Pembayaran -->
                    <div class="card" style="padding: 1.25rem; border-radius: var(--radius-sm); border-top: 4px solid var(--danger); display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; box-shadow: var(--shadow-sm);">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600);">Sisa Pembayaran</span>
                        <strong style="font-size: 1.25rem; color: var(--danger);">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Card 4: Status -->
                    <div class="card" style="padding: 1.25rem; border-radius: var(--radius-sm); background: {{ $statusBg }}; border: {{ $statusBorder }}; display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; box-shadow: var(--shadow-sm);">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--gray-600);">Status Pembayaran</span>
                        <strong style="font-size: 1.1rem; color: {{ $statusColor }};">{{ $statusText }}</strong>
                    </div>
                </div>

                <!-- Angsuran Slots Grid -->
                <div class="card" id="status-angsuran" style="padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
                    <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-money-bill-wave"></i> Status Angsuran Pembayaran (Maks. 4 Kali)
                    </h3>
                    <div class="grid grid-4" style="gap: 1.25rem;">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                            @endphp
                            <div style="border: 1px solid var(--gray-200); border-radius: var(--radius-sm); padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem; position: relative; background: var(--white);">
                                <div style="font-weight: 700; font-size: 0.9rem; color: var(--gray-800);">Pembayaran {{ $i }}</div>
                                @if($pembayaranAngsuran)
                                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--primary);">Rp {{ number_format($pembayaranAngsuran->jumlah, 0, ',', '.') }}</div>
                                    <div>
                                        <span class="badge badge-{{ $pembayaranAngsuran->status }}" style="font-size: 0.65rem; padding: 0.2rem 0.5rem;">
                                            {{ $pembayaranAngsuran->status }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.7rem; color: var(--gray-400);">Tgl: {{ \Carbon\Carbon::parse($pembayaranAngsuran->tanggal_bayar)->format('d-m-Y') }}</div>
                                    <div style="font-size: 0.7rem; color: var(--gray-500); margin-top: 0.1rem;">
                                        Metode: <strong style="text-transform: capitalize;">{{ $pembayaranAngsuran->metode_pembayaran === 'tunai' ? 'Tunai / Cash' : 'Transfer' }}</strong>
                                    </div>
                                    @if($pembayaranAngsuran->status === 'ditolak')
                                        <button type="button" class="btn btn-primary btn-sm" style="font-size: 0.7rem; padding: 0.25rem; margin-top: 0.5rem; width: 100%; color: white; border: none;" onclick="selectAngsuran({{ $i }})">Unggah Ulang</button>
                                    @else
                                        <a href="{{ asset('storage/' . $pembayaranAngsuran->bukti_bayar) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.7rem; padding: 0.25rem; margin-top: 0.5rem; width: 100%; text-align: center; text-decoration: none; color: var(--gray-800); border: 1px solid var(--gray-200); display: block;">Lihat Bukti</a>
                                    @endif
                                @else
                                    <div style="font-size: 0.8rem; color: var(--gray-400); font-style: italic; margin-bottom: 0.5rem;">
                                        {{ $sisaBayar == 0 ? 'Tidak Diperlukan (Lunas)' : 'Belum ada pembayaran' }}
                                    </div>
                                    @if($sisaBayar > 0)
                                        <button type="button" class="btn btn-primary btn-sm" style="font-size: 0.7rem; padding: 0.25rem; width: 100%; margin-top: auto; color: white; border: none;" onclick="selectAngsuran({{ $i }})">Bayar / Upload</button>
                                    @endif
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Subtab: Laporan Pembayaran -->
            <div id="subtab-laporan" class="subtab-content" style="display: none;">
                <!-- Formulir Bukti Pembayaran -->
                <div class="card" id="upload-form-card" style="padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                    <h4 style="color: var(--gray-800); margin-bottom: 1.25rem; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary);"></i> Formulir Bukti Pembayaran
                    </h4>
                    
                    @if($sisaBayar > 0)
                        <form action="{{ route('student.upload-pembayaran') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="angsuran_ke" class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--gray-800);">Pembayaran Ke- <span style="color: red;">*</span></label>
                                    <select name="angsuran_ke" id="angsuran_ke" class="form-control" required style="background: white;">
                                        @for($i = 1; $i <= 4; $i++)
                                            @php
                                                $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                                            @endphp
                                            @if(!$pembayaranAngsuran || $pembayaranAngsuran->status === 'ditolak')
                                                <option value="{{ $i }}">Pembayaran {{ $i }}</option>
                                            @else
                                                <option value="{{ $i }}" disabled style="color: var(--gray-400);">Pembayaran {{ $i }} (Lunas/Menunggu)</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group" style="margin: 0;">
                                    <label for="metode_pembayaran" class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--gray-800);">Metode Pembayaran <span style="color: red;">*</span></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required style="background: white;">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="tunai">Tunai / Cash di Sekolah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="jumlah" class="form-label" id="jumlah_label" style="font-weight: 600; font-size: 0.85rem; color: var(--gray-800);">Nominal yang Ditransfer (Rp) <span style="color: red;">*</span></label>
                                    <div id="jumlah-container" style="position: relative; display: flex; align-items: center; width: 100%;">
                                        <span style="position: absolute; left: 1rem; color: var(--gray-600); font-weight: 600; pointer-events: none; font-size: 0.9rem; z-index: 10;">Rp</span>
                                        <input type="text" name="jumlah" id="jumlah" class="form-control" placeholder="Contoh: {{ number_format($sisaBayar, 0, ',', '.') }}" value="{{ old('jumlah', $sisaBayar) }}" required style="padding-left: 2.5rem;">
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin: 0;">
                                    <label for="bukti_bayar" id="bukti_bayar_label" class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--gray-800);">File Bukti Transfer <span style="color: red;">*</span></label>
                                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control" required accept="image/*">
                                    <span style="font-size: 0.75rem; color: var(--gray-400); margin-top: 0.25rem; display: block;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                                </div>
                            </div>

                            <span style="font-size: 0.75rem; color: var(--danger); margin-bottom: 1.5rem; display: block; font-weight: 500;">* Pastikan bukti pembayaran terlihat jelas, asli, tidak terpotong, dan nominal sesuai.</span>

                            <button type="submit" class="btn btn-primary btn-block" style="padding: 0.75rem; font-weight: 700; width: 100%; border: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: white;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Bukti Pembayaran
                            </button>
                        </form>
                    @else
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1.5px solid rgba(16, 185, 129, 0.2); padding: 1.5rem; border-radius: var(--radius-sm); text-align: center; color: var(--success); font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-circle-check" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Seluruh Pembayaran Administrasi Anda Telah Lunas.
                        </div>
                    @endif
                </div>
            </div>

        @elseif($tab === 'pengumuman')
            <div class="dashboard-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--primary); font-size: 1.75rem;">Pengumuman Hasil Seleksi</h1>
                    <p style="color: var(--gray-600);">Monitor hasil kelulusan penerimaan siswa baru secara berkala.</p>
                </div>
            </div>

            <div class="card" style="padding: 2.5rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid var(--gray-200); padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; color: var(--primary);"><i class="fa-solid fa-circle-info" style="margin-right: 0.5rem;"></i> Status Seleksi PPDB</h3>
                    <span class="badge badge-{{ $pendaftaran->status }}" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        Status: {{ ucfirst($pendaftaran->status) }}
                    </span>
                </div>

                <p class="card-text" style="font-size: 1.05rem; line-height: 1.75; margin-bottom: 1.5rem;">
                    @if($pendaftaran->status === 'menunggu')
                        Formulir pendaftaran Anda telah tersimpan di sistem. Tahap berikutnya adalah **mengunggah berkas persyaratan** (terutama KK wajib) dan melakukan **pembayaran administrasi** pada tab **Administrasi**.
                    @elseif($pendaftaran->status === 'verifikasi')
                        Berkas dan bukti pembayaran Anda sedang dalam proses pemeriksaan dan verifikasi oleh panitia PPDB SMK Mandiri Pontianak.
                    @elseif($pendaftaran->status === 'diterima')
                        Selamat! Anda dinyatakan **Diterima** sebagai siswa baru di SMK Mandiri Pontianak untuk Program Keahlian **{{ $siswa->jurusan }}**. Silakan cetak bukti kelulusan Anda menggunakan tombol di bawah ini.
                    @elseif($pendaftaran->status === 'ditolak')
                        Mohon maaf, pendaftaran Anda dinyatakan **Ditolak** atau ditangguhkan oleh panitia.
                    @endif
                </p>
                
                @if($pendaftaran->keterangan)
                    <div style="background: var(--gray-100); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 2rem; font-size: 0.95rem; border-left: 4px solid var(--primary);">
                        <strong>Catatan Panitia:</strong>
                        <p style="margin-top: 0.5rem; color: var(--gray-600); font-style: italic;">"{{ $pendaftaran->keterangan }}"</p>
                    </div>
                @endif

                @if($pendaftaran->status === 'diterima')
                    <div>
                        <a href="{{ route('student.print') }}" target="_blank" class="btn btn-success" style="padding: 0.75rem 2rem; font-weight: 600;">
                            <i class="fa-solid fa-print"></i> Cetak Bukti Kelulusan
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </main>
</div>
@endsection

@section('scripts')
    <script>
        function handleAdministrasiClick(event) {
            const isAdministrasi = {{ $tab === 'administrasi' ? 'true' : 'false' }};
            const submenu = document.getElementById('administrasi-submenu');
            const chevron = document.getElementById('administrasi-chevron');
            
            if (isAdministrasi) {
                event.preventDefault();
                if (submenu) {
                    if (submenu.classList.contains('expanded')) {
                        submenu.classList.remove('expanded');
                        submenu.classList.add('collapsed');
                        if (chevron) chevron.classList.remove('rotated');
                    } else {
                        submenu.classList.remove('collapsed');
                        submenu.classList.add('expanded');
                        if (chevron) chevron.classList.add('rotated');
                    }
                }
            }
        }

        function switchSubtab(subtabId) {
            // Ensure submenu is expanded when a subtab is clicked
            const submenu = document.getElementById('administrasi-submenu');
            const chevron = document.getElementById('administrasi-chevron');
            if (submenu && submenu.classList.contains('collapsed')) {
                submenu.classList.remove('collapsed');
                submenu.classList.add('expanded');
                if (chevron) chevron.classList.add('rotated');
            }

            // Hide all subtab content
            document.querySelectorAll('.subtab-content').forEach(function(el) {
                el.style.display = 'none';
            });
            
            // Show selected subtab content
            const targetEl = document.getElementById('subtab-' + subtabId);
            if (targetEl) {
                targetEl.style.display = 'block';
            }
            
            // Remove active class from all submenu items
            document.querySelectorAll('.sidebar-submenu li').forEach(function(li) {
                li.classList.remove('active');
            });
            
            // Add active class to clicked submenu item
            const activeLi = document.getElementById('submenu-' + subtabId);
            if (activeLi) {
                activeLi.classList.add('active');
            }
            
            // Save to hash so state is preserved on navigation
            history.pushState(null, null, '#subtab-' + subtabId);
        }

        function toggleUploadForm(key) {
            const formattedKey = key.replace(' ', '-');
            const form = document.getElementById('form-upload-' + formattedKey);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function selectAngsuran(slotNum) {
            // Switch to the 'laporan' subtab before scrolling and updating inputs
            switchSubtab('laporan');

            const selectEl = document.getElementById('angsuran_ke');
            if (selectEl) {
                selectEl.value = slotNum;
                
                // Trigger change event to update the amount
                const event = new Event('change');
                selectEl.dispatchEvent(event);
                
                // Focus and scroll to card
                const cardEl = document.getElementById('upload-form-card');
                if (cardEl) {
                    setTimeout(() => {
                        cardEl.scrollIntoView({ behavior: 'smooth' });
                    }, 150);
                }
            }
        }

        function formatRupiah(value) {
            if (!value) return '';
            let number = String(value).replace(/[^0-9]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check hash on page load
            const hash = window.location.hash;
            if (hash && hash.startsWith('#subtab-')) {
                const subtabId = hash.replace('#subtab-', '');
                switchSubtab(subtabId);
            } else {
                // Default to rincian if we are in administrasi tab
                const isAdministrasi = {{ $tab === 'administrasi' ? 'true' : 'false' }};
                if (isAdministrasi) {
                    switchSubtab('rincian');
                }
            }

            const angsuranSelect = document.getElementById('angsuran_ke');
            const sisaBayar = {{ $sisaBayar ?? 0 }};
            
            function updateJumlahBasedOnAngsuran() {
                const selectEl = document.getElementById('angsuran_ke');
                const container = document.getElementById('jumlah-container');
                if (!selectEl || !container) return;
                
                const val = selectEl.value;
                if (val === '1') {
                    container.innerHTML = `
                        <span style="position: absolute; left: 1rem; color: var(--gray-600); font-weight: 600; pointer-events: none; font-size: 0.9rem; z-index: 10;">Rp</span>
                        <input type="text" name="jumlah" id="jumlah" class="form-control" value="1.500.000" readonly style="padding-left: 2.5rem; background: #e2e8f0; cursor: not-allowed;">
                    `;
                } else if (val === '4') {
                    container.innerHTML = `
                        <span style="position: absolute; left: 1rem; color: var(--gray-600); font-weight: 600; pointer-events: none; font-size: 0.9rem; z-index: 10;">Rp</span>
                        <input type="text" name="jumlah" id="jumlah" class="form-control" value="${formatRupiah(sisaBayar)}" readonly style="padding-left: 2.5rem; background: #e2e8f0; cursor: not-allowed;">
                    `;
                } else {
                    let optionsHtml = '';
                    const maxVal = Math.min(500000, sisaBayar);
                    let step = 50000;
                    let addedValues = new Set();
                    
                    for (let amount = 50000; amount <= maxVal; amount += step) {
                        optionsHtml += `<option value="${amount}">Rp ${formatRupiah(amount)}</option>`;
                        addedValues.add(amount);
                    }
                    
                    if (sisaBayar < 50000 && sisaBayar > 0) {
                        optionsHtml += `<option value="${sisaBayar}">Rp ${formatRupiah(sisaBayar)} (Pelunasan / Sisa Tagihan)</option>`;
                        addedValues.add(sisaBayar);
                    }
                    
                    if (!addedValues.has(sisaBayar) && sisaBayar > 0) {
                        optionsHtml += `<option value="${sisaBayar}">Rp ${formatRupiah(sisaBayar)} (Pelunasan / Sisa Tagihan)</option>`;
                    }
                    
                    container.innerHTML = `
                        <select name="jumlah" id="jumlah" class="form-control" style="background: white;" required>
                            ${optionsHtml}
                        </select>
                    `;
                }
            }

            if (angsuranSelect) {
                angsuranSelect.addEventListener('change', updateJumlahBasedOnAngsuran);
                updateJumlahBasedOnAngsuran();
            }

            const metodePembayaranSelect = document.getElementById('metode_pembayaran');
            const buktiBayarLabel = document.getElementById('bukti_bayar_label');
            const jumlahLabel = document.getElementById('jumlah_label');

            function updatePaymentMethodLabels() {
                if (!metodePembayaranSelect || !buktiBayarLabel || !jumlahLabel) return;

                if (metodePembayaranSelect.value === 'tunai') {
                    buktiBayarLabel.innerHTML = 'File Kwitansi Pembayaran Resmi <span style="color: red;">*</span>';
                    jumlahLabel.innerHTML = 'Nominal yang Dibayarkan (Rp) <span style="color: red;">*</span>';
                } else {
                    buktiBayarLabel.innerHTML = 'File Bukti Transfer <span style="color: red;">*</span>';
                    jumlahLabel.innerHTML = 'Nominal yang Ditransfer (Rp) <span style="color: red;">*</span>';
                }
            }

            if (metodePembayaranSelect) {
                metodePembayaranSelect.addEventListener('change', updatePaymentMethodLabels);
                updatePaymentMethodLabels();
            }

            const form = document.getElementById('angsuran_ke')?.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const jumlahInput = document.getElementById('jumlah');
                    if (jumlahInput) {
                        jumlahInput.value = String(jumlahInput.value).replace(/\./g, '');
                    }
                });
            }
        });
    </script>
@endsection
