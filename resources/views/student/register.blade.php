@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Calon Siswa')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Sidebar Header -->
        <div class="student-sidebar-header">
            <h3>PPDB SMK</h3>
            <p>Tahun Ajaran 2025/2026</p>
        </div>

        <nav class="sidebar-menu">
            <a href="#" class="sidebar-link active">
                <i class="fa-solid fa-file-signature"></i> Pendaftaran
            </a>
            <a href="#" class="sidebar-link" style="cursor: not-allowed; opacity: 0.6;" onclick="alert('Silakan lengkapi formulir pendaftaran terlebih dahulu.')">
                <i class="fa-solid fa-receipt"></i> Administrasi
            </a>
            <a href="#" class="sidebar-link" style="cursor: not-allowed; opacity: 0.6;" onclick="alert('Silakan lengkapi formulir pendaftaran terlebih dahulu.')">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </a>
        </nav>

        <!-- User Widget -->
        <div class="sidebar-user-widget" style="display: flex; flex-direction: column; gap: 0.75rem; align-items: stretch; margin-top: 1.5rem; margin-bottom: 0;">
            <div style="display: flex; align-items: center; gap: 0.75rem; width: 100%;">
                <div class="sidebar-user-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                    <span class="sidebar-user-role">Siswa</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger btn-block btn-sm" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem; width: 100%;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="dashboard-content">
        <div class="dashboard-header" style="margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Formulir Pendaftaran</h1>
                <p style="color: var(--gray-600);">Lengkapi berkas dan data diri calon siswa baru di bawah ini.</p>
            </div>
        </div>

        <div class="card" style="padding: 2.5rem; margin-bottom: 2rem; border-radius: var(--radius-md);">
            <form action="{{ route('student.register.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section: Data Pribadi -->
                <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user" style="color: var(--secondary-dark);"></i> Data Pribadi</h3>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control uppercase-input" placeholder="Nama Lengkap" value="{{ old('nama', auth()->user()->name) }}" required>
                        @error('nama') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional) <span style="color: red;">*</span></label>
                        <input type="text" name="nisn" id="nisn" class="form-control" placeholder="10 digit NISN" value="{{ old('nisn', auth()->user()->nisn) }}" required minlength="10" maxlength="10" readonly>
                        @error('nisn') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">Kota Kelahiran <span style="color: red;">*</span></label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control uppercase-input" placeholder="Kota Kelahiran" value="{{ old('tempat_lahir') }}" required>
                        @error('tempat_lahir') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                        @error('tanggal_lahir') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Jenis kelamin <span style="color: red;">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="agama" class="form-label">Agama <span style="color: red;">*</span></label>
                        <select name="agama" id="agama" class="form-control" required>
                            <option value="" disabled selected>Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat Tempat Tinggal <span style="color: red;">*</span></label>
                        <input type="text" name="alamat" id="alamat" class="form-control uppercase-input" placeholder="alamat tempat tinggal" value="{{ old('alamat') }}" required>
                        @error('alamat') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kewarganegaraan" class="form-label">Kewarganegaraan <span style="color: red;">*</span></label>
                        <input type="text" name="kewarganegaraan" id="kewarganegaraan" class="form-control uppercase-input" placeholder="Kewarganegaraan" value="{{ old('kewarganegaraan', 'WNI') }}" required>
                        @error('kewarganegaraan') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="provinsi" class="form-label">Provinsi <span style="color: red;">*</span></label>
                        <select name="provinsi" id="provinsi" class="form-control" required style="background: white;">
                            <option value="" disabled>Pilih Provinsi</option>
                            @php
                                $daftar_provinsi = [
                                    'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
                                    'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali',
                                    'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi', 'Sumatera Selatan',
                                    'Kepulauan Bangka Belitung', 'Bengkulu', 'Lampung', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
                                    'Sulawesi Utara', 'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara',
                                    'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya'
                                ];
                                $current_provinsi = old('provinsi', 'Kalimantan Barat');
                            @endphp
                            @foreach($daftar_provinsi as $p)
                                <option value="{{ $p }}" {{ $current_provinsi == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('provinsi') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kota" class="form-label">Kota / Kabupaten <span style="color: red;">*</span></label>
                        <select name="kota" id="kota" class="form-control" required style="background: white;">
                            <option value="" disabled selected>Pilih Kota / Kabupaten</option>
                            @php
                                $daftar_kota = [
                                    'Kabupaten Sambas', 'Kabupaten Bengkayang', 'Kabupaten Landak', 'Kabupaten Mempawah',
                                    'Kabupaten Sanggau', 'Kabupaten Ketapang', 'Kabupaten Sintang', 'Kabupaten Kapuas Hulu',
                                    'Kabupaten Sekadau', 'Kabupaten Melawi', 'Kabupaten Kayong Utara', 'Kabupaten Kubu Raya',
                                    'Kota Pontianak', 'Kota Singkawang'
                                ];
                                $current_kota = old('kota');
                            @endphp
                            @foreach($daftar_kota as $k)
                                <option value="{{ $k }}" {{ $current_kota == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                        @error('kota') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kecamatan" class="form-label">Kecamatan <span style="color: red;">*</span></label>
                        <select name="kecamatan" id="kecamatan" class="form-control" required style="background: white;">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                            @php
                                $daftar_kecamatan = [
                                    'Pontianak Timur', 'Pontianak Barat', 'Pontianak Kota', 'Pontianak Selatan',
                                    'Pontianak Tenggara', 'Pontianak Utara', 
                                    'Kecamatan Sungai Raya', 'Kecamatan Sungai Kakap',
                                    'Kecamatan Sungai Ambawang', 'Kecamatan Kubu', 'Kecamatan Rasau Jaya',
                                    'Kecamatan Teluk Pakedai', 'Kecamatan Batu Ampar', 'Kecamatan Terentang',
                                    'Kecamatan Kuala Mandor-B',
                                    'Siantan', 'Mempawah Hilir', 'Mempawah Timur'
                                ];
                                $current_kecamatan = old('kecamatan');
                            @endphp
                            @foreach($daftar_kecamatan as $kec)
                                <option value="{{ $kec }}" {{ $current_kecamatan == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                            @endforeach
                        </select>
                        @error('kecamatan') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kelurahan" class="form-label">Kelurahan / Desa <span style="color: red;">*</span></label>
                        <select name="kelurahan" id="kelurahan" class="form-control" required style="background: white;">
                            <option value="" disabled selected>Pilih Kelurahan / Desa</option>
                            @php
                                $daftar_kelurahan = [
                                    'Darat Sekip', 'Mariana', 'Sungai Bangkong', 'Sungai Jawi', 'Tengah',
                                    'Pal Lima', 'Sungai Beliung', 'Sungaijawi Dalam', 'Sungaijawi Luar',
                                    'Akcaya', 'Benua Melayu Darat', 'Benua Melayu Laut', 'Kota Baru', 'Parit Tokaya',
                                    'Bangka Belitung Darat', 'Bangka Belitung Laut', 'Bansir Darat', 'Bansir Laut',
                                    'Banjar Serasan', 'Dalam Bugis', 'Parit Mayor', 'Saigon', 'Tambelan Sampit', 'Tanjung Hulu', 'Tanjung Hilir',
                                    'Batu Layang', 'Siantan Hilir', 'Siantan Hulu', 'Siantan Tengah',
                                    'Sungai Raya', 'Sungai Ambangah', 'Arang Limbung', 'Kuala Dua', 'Tebang Kacang', 'Sungai Asam', 'Pulau Limbung', 'Desa Kapur', 'Gunung Tamang', 'Sungai Bulan', 'Limbung', 'Teluk Kapuas', 'Madu Sari', 'Mekar Sari', 'Mekar Baru', 'Sungai Raya Dalam', 'Parit Baru', 'Pulau Jambu', 'Kalibandung', 'Muara Baru', 'Suku Lanting', 'Permata Jaya',
                                    'Jeruju Besar', 'Kalimas', 'Pal Sembilan (Pal IX)', 'Punggur Besar', 'Punggur Kapuas', 'Punggur Kecil', 'Sungai Belidak', 'Sungai Itik', 'Sungai Kakap', 'Sungai Kupah', 'Sungai Rengas', 'Sepuk Laut', 'Tanjung Saleh',
                                    'Ampera Raya', 'Bengkarek', 'Durian', 'Jawa Tengah', 'Korek', 'Lingga', 'Mega Timur', 'Pancaroba', 'Pasak', 'Pasak Piang', 'Puguk', 'Simpang Kanan', 'Simpang Raya', 'Sungai Ambawang Kuala', 'Sungai Malaya', 'Teluk Bakung',
                                    'Air Putih', 'Ambarawa', 'Dabong', 'Jangkang Dua', 'Jangkang Satu', 'Kampung Baru', 'Kubu', 'Mengkalang', 'Mengkalang Jambu', 'Olak-Olak', 'Pelita Jaya', 'Pinang Dalam', 'Pinang Luar', 'Sungai Bemban', 'Sungai Selamat', 'Sungai Terus', 'Sepakat Baru', 'Seruat Dua', 'Seruat Tiga', 'Teluk Nangka',
                                    'Mempawah Hilir', 'Mempawah Timur', 'Siantan'
                                ];
                                $current_kelurahan = old('kelurahan');
                            @endphp
                            @foreach($daftar_kelurahan as $kel)
                                <option value="{{ $kel }}" {{ $current_kelurahan == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                            @endforeach
                        </select>
                        @error('kelurahan') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="no_hp" class="form-label">No. Handphone <span style="color: red;">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="no.handpone" value="{{ old('no_hp', auth()->user()->no_hp) }}" required>
                        @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jumlah_saudara" class="form-label">Jumlah Saudara Kandung <span style="color: red;">*</span></label>
                        <input type="number" name="jumlah_saudara" id="jumlah_saudara" class="form-control" placeholder="Jumlah saudara kandung" value="{{ old('jumlah_saudara') }}" required>
                        @error('jumlah_saudara') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="anak_ke" class="form-label">Anak Ke- <span style="color: red;">*</span></label>
                        <input type="number" name="anak_ke" id="anak_ke" class="form-control" placeholder="Anak ke- (contoh: 1)" value="{{ old('anak_ke') }}" required min="1">
                        @error('anak_ke') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm) <span style="color: red;">*</span></label>
                        <input type="number" name="tinggi_badan" id="tinggi_badan" class="form-control" placeholder="Tinggi Badan" value="{{ old('tinggi_badan') }}" required>
                        @error('tinggi_badan') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="penyakit" class="form-label">Penyakit Pernah Diderita (jika ada) <span style="color: red;">*</span></label>
                        <input type="text" name="penyakit" id="penyakit" class="form-control uppercase-input" placeholder="penyakit pernah diderita (jika ada)" value="{{ old('penyakit') }}" required>
                        @error('penyakit') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-grid-2" style="margin-top: 2rem;">
                    <!-- Section: Data Ayah -->
                    <div>
                        <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user-tie" style="color: var(--secondary-dark);"></i> Data Ayah</h3>
                        <div class="form-group">
                            <label for="nama_ayah" class="form-label">Nama Ayah <span style="color: red;">*</span></label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control uppercase-input" placeholder="nama ayah" value="{{ old('nama_ayah') }}" required>
                            @error('nama_ayah') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan_ayah" class="form-label">Pekerjaan <span style="color: red;">*</span></label>
                            <select name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" style="background: white;">
                                <option value="" disabled selected>Pilih Pekerjaan</option>
                                @php
                                    $daftar_pekerjaan = [
                                        'Tidak Bekerja', 'Pegawai Negeri Sipil (PNS)', 'TNI', 'POLRI',
                                        'Karyawan Swasta', 'Wiraswasta', 'Pedagang', 'Petani', 'Peternak',
                                        'Nelayan', 'Buruh', 'Pekerja Harian', 'Lainnya'
                                    ];
                                    $val = old('pekerjaan_ayah');
                                @endphp
                                @foreach($daftar_pekerjaan as $p)
                                    <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('pekerjaan_ayah') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="pendidikan_ayah" class="form-label">Pendidikan Terakhir <span style="color: red;">*</span></label>
                            <select name="pendidikan_ayah" id="pendidikan_ayah" class="form-control" style="background: white;">
                                <option value="" disabled selected>Pilih Pendidikan Terakhir</option>
                                @php
                                    $daftar_pendidikan = [
                                        'Tidak Sekolah', 'SD', 'SMP', 'SMA', 'SMK',
                                        'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'
                                    ];
                                    $val = old('pendidikan_ayah');
                                @endphp
                                @foreach($daftar_pendidikan as $p)
                                    <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('pendidikan_ayah') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="penghasilan_ayah" class="form-label">Penghasilan Bulanan <span style="color: red;">*</span></label>
                            <select name="penghasilan_ayah" id="penghasilan_ayah" class="form-control" style="background: white;">
                                <option value="" disabled selected>Pilih Penghasilan Bulanan</option>
                                @php
                                    $daftar_penghasilan = [
                                        'Tidak Berpenghasilan', 'Kurang dari Rp 1.000.000', 'Rp 1.000.000 - Rp 2.000.000',
                                        'Rp 2.000.000 - Rp 3.000.000', 'Rp 3.000.000 - Rp 4.000.000', 'Rp 4.000.000 - Rp 5.000.000',
                                        'Rp 5.000.000 - Rp 7.500.000', 'Rp 7.500.000 - Rp 10.000.000', 'Rp 10.000.000 - Rp 15.000.000',
                                        'Lebih dari Rp 15.000.000'
                                    ];
                                    $val = old('penghasilan_ayah');
                                @endphp
                                @foreach($daftar_penghasilan as $p)
                                    <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('penghasilan_ayah') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Section: Data Wali -->
                    <div>
                        <h3 class="form-section-title" style="margin-top: 0;"><i class="fa-solid fa-user-shield" style="color: var(--secondary-dark);"></i> Data Wali (jika ada)</h3>
                        <div class="form-group">
                            <label for="nama_wali" class="form-label">Nama Wali</label>
                            <input type="text" name="nama_wali" id="nama_wali" class="form-control uppercase-input" placeholder="nama wali" value="{{ old('nama_wali') }}">
                            @error('nama_wali') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan_wali" class="form-label">Pekerjaan</label>
                            <select name="pekerjaan_wali" id="pekerjaan_wali" class="form-control" style="background: white;">
                                <option value="" disabled selected>Pilih Pekerjaan</option>
                                @php
                                    $daftar_pekerjaan = [
                                        'Tidak Bekerja', 'Pegawai Negeri Sipil (PNS)', 'TNI', 'POLRI',
                                        'Karyawan Swasta', 'Wiraswasta', 'Pedagang', 'Petani', 'Peternak',
                                        'Nelayan', 'Buruh', 'Pekerja Harian', 'Lainnya'
                                    ];
                                    $val = old('pekerjaan_wali');
                                @endphp
                                @foreach($daftar_pekerjaan as $p)
                                    <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('pekerjaan_wali') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat_wali" class="form-label">Alamat Wali</label>
                            <input type="text" name="alamat_wali" id="alamat_wali" class="form-control uppercase-input" placeholder="alamat wali" value="{{ old('alamat_wali') }}">
                            @error('alamat_wali') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_hp_wali" class="form-label">No HP Wali</label>
                            <input type="text" name="no_hp_wali" id="no_hp_wali" class="form-control" placeholder="no hp wali" value="{{ old('no_hp_wali') }}">
                            @error('no_hp_wali') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Data Ibu -->
                <h3 class="form-section-title"><i class="fa-solid fa-user-nurse" style="color: var(--secondary-dark);"></i> Data Ibu</h3>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="nama_ibu" class="form-label">Nama Ibu <span style="color: red;">*</span></label>
                        <input type="text" name="nama_ibu" id="nama_ibu" class="form-control uppercase-input" placeholder="nama ibu" value="{{ old('nama_ibu') }}" required>
                        @error('nama_ibu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="penghasilan_ibu" class="form-label">Penghasilan Bulanan <span style="color: red;">*</span></label>
                        <select name="penghasilan_ibu" id="penghasilan_ibu" class="form-control" style="background: white;">
                            <option value="" disabled selected>Pilih Penghasilan Bulanan</option>
                            @php
                                $daftar_penghasilan = [
                                    'Tidak Berpenghasilan', 'Kurang dari Rp 1.000.000', 'Rp 1.000.000 - Rp 2.000.000',
                                    'Rp 2.000.000 - Rp 3.000.000', 'Rp 3.000.000 - Rp 4.000.000', 'Rp 4.000.000 - Rp 5.000.000',
                                    'Rp 5.000.000 - Rp 7.500.000', 'Rp 7.500.000 - Rp 10.000.000', 'Rp 10.000.000 - Rp 15.000.000',
                                    'Lebih dari Rp 15.000.000'
                                ];
                                $val = old('penghasilan_ibu');
                            @endphp
                            @foreach($daftar_penghasilan as $p)
                                <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('penghasilan_ibu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan_ibu" class="form-label">Pekerjaan <span style="color: red;">*</span></label>
                        <select name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" style="background: white;">
                            <option value="" disabled selected>Pilih Pekerjaan</option>
                            @php
                                $daftar_pekerjaan = [
                                    'Tidak Bekerja', 'Ibu Rumah Tangga', 'Pegawai Negeri Sipil (PNS)', 'TNI', 'POLRI',
                                    'Karyawan Swasta', 'Wiraswasta', 'Pedagang', 'Petani', 'Peternak',
                                    'Nelayan', 'Buruh', 'Pekerja Harian', 'Lainnya'
                                ];
                                $val = old('pekerjaan_ibu');
                            @endphp
                            @foreach($daftar_pekerjaan as $p)
                                <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pekerjaan_ibu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="no_hp_ortu" class="form-label">No Handphone Ayah/Ibu <span style="color: red;">*</span></label>
                        <input type="text" name="no_hp_ortu" id="no_hp_ortu" class="form-control" placeholder="no handpone ayah/ibu" value="{{ old('no_hp_ortu') }}">
                        @error('no_hp_ortu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="pendidikan_ibu" class="form-label">Pendidikan Terakhir <span style="color: red;">*</span></label>
                        <select name="pendidikan_ibu" id="pendidikan_ibu" class="form-control" style="background: white;">
                            <option value="" disabled selected>Pilih Pendidikan Terakhir</option>
                            @php
                                $daftar_pendidikan = [
                                    'Tidak Sekolah', 'SD', 'SMP', 'SMA', 'SMK',
                                    'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'
                                ];
                                $val = old('pendidikan_ibu');
                            @endphp
                            @foreach($daftar_pendidikan as $p)
                                <option value="{{ $p }}" {{ $val == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pendidikan_ibu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Section: Pilih Jurusan -->
                <h3 class="form-section-title"><i class="fa-solid fa-graduation-cap" style="color: var(--secondary-dark);"></i> Pilih Jurusan</h3>
                <div class="form-group">
                    <label for="jurusan" class="form-label">Pilih Jurusan <span style="color: red;">*</span></label>
                    <select name="jurusan" id="jurusan" class="form-control" required>
                        <option value="" disabled selected>Pilih Jurusan</option>
                        <option value="Animasi" {{ old('jurusan') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                        <option value="Akuntansi" {{ old('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="Pemasaran" {{ old('jurusan') == 'Pemasaran' ? 'selected' : '' }}>Pemasaran</option>
                    </select>
                    @error('jurusan') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <!-- Section: Upload Dokumen -->
                <h3 class="form-section-title"><i class="fa-solid fa-cloud-arrow-up" style="color: var(--secondary-dark);"></i> Upload Dokumen</h3>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="file_ijazah" class="form-label">Ijazah atau SKHU <span style="color: red;">*</span></label>
                        <input type="file" name="file_ijazah" id="file_ijazah" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_ijazah') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file_kk" class="form-label">KK (Kartu Keluarga) <span style="color: red;">*</span></label>
                        <input type="file" name="file_kk" id="file_kk" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_kk') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file_akta" class="form-label">Akta Kelahiran <span style="color: red;">*</span></label>
                        <input type="file" name="file_akta" id="file_akta" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_akta') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file_ktp_ortu" class="form-label">KTP Orang Tua <span style="color: red;">*</span></label>
                        <input type="file" name="file_ktp_ortu" id="file_ktp_ortu" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_ktp_ortu') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file_kps" class="form-label">KPS (Kartu Perlindungan Sosial) (jika ada)</label>
                        <input type="file" name="file_kps" id="file_kps" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_kps') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file_kip" class="form-label">KIP (Kartu Indonesia Pintar) (jika ada)</label>
                        <input type="file" name="file_kip" id="file_kip" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <span style="font-size: 0.75rem; color: var(--gray-400);">Format: JPG, PNG, PDF (Max. 2MB)</span>
                        @error('file_kip') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Declaration -->
                <div style="margin: 2.5rem 0 1.5rem 0; display: flex; gap: 0.75rem; align-items: flex-start; background: var(--gray-100); padding: 1.25rem; border-radius: var(--radius-sm); border-left: 4px solid var(--secondary-dark);">
                    <input type="checkbox" name="pernyataan" id="pernyataan" required style="width: 18px; height: 18px; margin-top: 3px; cursor: pointer;">
                    <label for="pernyataan" style="font-size: 0.9rem; color: var(--gray-800); line-height: 1.5; cursor: pointer;">
                        Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. jika dikemudian hari terdapat ketidaksesuaian data, saya bersedia menerima sanksi yang berlaku.
                    </label>
                    @error('pernyataan') <span class="form-error" style="display: block; width: 100%;">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 0.85rem; font-size: 1.05rem; font-weight: 700; border-radius: var(--radius-sm);">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
                </button>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    window.addressConfig = {
        provinsi: "{{ old('provinsi', 'Kalimantan Barat') }}",
        kota: "{{ old('kota') }}",
        kecamatan: "{{ old('kecamatan') }}",
        kelurahan: "{{ old('kelurahan') }}"
    };
</script>
<script src="{{ asset('js/address-sync.js') }}?v=1.0.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupNumericValidation(inputId, targetLength) {
        const inputEl = document.getElementById(inputId);
        if (!inputEl) return;

        // Ensure max length attribute is set on the input
        inputEl.setAttribute('maxlength', targetLength);

        // Create or find warning elements
        let warningEl = document.getElementById(inputId + '-warning');
        if (!warningEl) {
            warningEl = document.createElement('span');
            warningEl.id = inputId + '-warning';
            warningEl.className = 'digit-warning';
            warningEl.style.display = 'block';
            warningEl.style.fontSize = '0.8rem';
            warningEl.style.marginTop = '0.25rem';
            warningEl.style.fontWeight = '500';
            inputEl.parentNode.appendChild(warningEl);
        }

        function validate() {
            // Remove non-digit characters
            let val = inputEl.value.replace(/\D/g, '');
            
            // Limit to targetLength
            if (val.length > targetLength) {
                val = val.substring(0, targetLength);
            }
            
            inputEl.value = val;

            if (val.length === 0) {
                warningEl.textContent = '';
                warningEl.style.color = '';
            } else if (val.length < targetLength) {
                warningEl.textContent = `⚠️ Angka kurang (${val.length} dari ${targetLength} digit)`;
                warningEl.style.color = '#ef4444'; // red
            } else {
                warningEl.textContent = `✅ Lengkap (${targetLength} digit)`;
                warningEl.style.color = '#10b981'; // green
            }
        }

        inputEl.addEventListener('input', validate);
        // Also run once in case there is old value
        validate();
    }

    setupNumericValidation('nisn', 10);

    const noHpInput = document.getElementById('no_hp');
    const noHpOrtuInput = document.getElementById('no_hp_ortu');

    // Create or find phone warning elements
    let phoneWarningEl = document.getElementById('no_hp_ortu-phone-warning');
    if (!phoneWarningEl && noHpOrtuInput) {
        phoneWarningEl = document.createElement('span');
        phoneWarningEl.id = 'no_hp_ortu-phone-warning';
        phoneWarningEl.className = 'form-error';
        phoneWarningEl.style.display = 'block';
        phoneWarningEl.style.fontSize = '0.8rem';
        phoneWarningEl.style.marginTop = '0.25rem';
        phoneWarningEl.style.color = '#ef4444';
        phoneWarningEl.style.fontWeight = '500';
        noHpOrtuInput.parentNode.appendChild(phoneWarningEl);
    }

    function checkPhoneNumbers() {
        if (noHpInput && noHpOrtuInput) {
            const noHp = noHpInput.value.trim();
            const noHpOrtu = noHpOrtuInput.value.trim();

            if (noHp !== '' && noHpOrtu !== '' && noHp === noHpOrtu) {
                phoneWarningEl.textContent = '⚠️ Nomor HP Orang Tua tidak boleh sama dengan Nomor HP Siswa';
                noHpOrtuInput.setCustomValidity('Nomor HP tidak boleh sama');
            } else {
                phoneWarningEl.textContent = '';
                noHpOrtuInput.setCustomValidity('');
            }
        }
    }

    if (noHpInput && noHpOrtuInput) {
        noHpInput.addEventListener('input', checkPhoneNumbers);
        noHpOrtuInput.addEventListener('input', checkPhoneNumbers);
        checkPhoneNumbers();
    }
});
</script>
@endsection
