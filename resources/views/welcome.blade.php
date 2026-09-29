@extends('layouts.app')

@section('title', 'Penerimaan Peserta Didik Baru')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">PPDB Tahun Ajaran 2026/2027</span>
            <h1 class="hero-title">Ayo Daftar Sekarang di SMK Mandiri Pontianak</h1>
            <p class="hero-subtitle">Pendaftaran online resmi dibuka mulai tanggal 1 Mei s.d 31 Juli. Pilih jurusan impianmu dan bergabunglah bersama kami!</p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                @auth
                    @if(auth()->user()->role === 'siswa')
                        <a href="{{ route('student.dashboard') }}" class="btn btn-accent"><i class="fa-solid fa-gauge"></i> Masuk Dashboard Anda</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-accent"><i class="fa-solid fa-gauge"></i> Dashboard Panitia</a>
                    @endif
                @else
                    @if(\App\Models\Setting::get('pendaftaran_status', 'dibuka') === 'ditutup')
                        <a href="{{ route('login') }}" class="btn btn-accent"><i class="fa-solid fa-right-to-bracket"></i> Masuk Portal PPDB</a>
                        <a href="{{ route('about') }}" class="btn btn-secondary">Pelajari Selengkapnya</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-accent">Mulai Pendaftaran <i class="fa-solid fa-arrow-right"></i></a>
                        <a href="{{ route('about') }}" class="btn btn-secondary">Pelajari Selengkapnya</a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <!-- Pendaftaran Schedule & Capacity -->
    <section class="section" style="padding-bottom: 2rem;">
        <div class="grid grid-2">
            <div class="card" style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.02), rgba(0, 198, 255, 0.02)); border-left: 5px solid var(--primary);">
                <h3 class="card-title" style="color: var(--primary);"><i class="fa-solid fa-calendar-days" style="margin-right: 0.5rem;"></i> Jadwal Pendaftaran</h3>
                <p class="card-text" style="margin-bottom: 1.5rem;">Penerimaan Peserta Didik Baru (PPDB) SMK Mandiri Pontianak dilaksanakan secara online:</p>
                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                    <div style="background: var(--primary); color: white; padding: 0.75rem 1rem; border-radius: var(--radius-sm); text-align: center; font-weight: bold; min-width: 80px;">
                        01<br><small>MEI</small>
                    </div>
                    <div>
                        <strong style="color: var(--primary);">Pembukaan Pendaftaran</strong>
                        <p class="card-text">Calon siswa mulai dapat membuat akun dan melengkapi berkas data diri.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="background: var(--danger); color: white; padding: 0.75rem 1rem; border-radius: var(--radius-sm); text-align: center; font-weight: bold; min-width: 80px;">
                        31<br><small>JULI</small>
                    </div>
                    <div>
                        <strong style="color: var(--danger);">Penutupan Pendaftaran</strong>
                        <p class="card-text">Batas akhir pengisian formulir, upload berkas, dan pelunasan biaya pendaftaran.</p>
                    </div>
                </div>
            </div>

            <div class="card" style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.02), rgba(0, 198, 255, 0.02)); border-left: 5px solid var(--secondary-dark);">
                <h3 class="card-title" style="color: var(--secondary-dark);"><i class="fa-solid fa-users-viewfinder" style="margin-right: 0.5rem;"></i> Daya Tampung Kuota</h3>
                <p class="card-text" style="margin-bottom: 1.5rem;">Daya tampung keseluruhan untuk Tahun Ajaran 2026/2027 adalah <strong>192 siswa</strong> dengan rincian kelas per program keahlian:</p>
                <ul style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <li style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid var(--gray-200);">
                        <span><i class="fa-solid fa-palette" style="color: var(--secondary-dark); width: 25px;"></i> Animasi (2 Kelas)</span>
                        <strong style="color: var(--primary);">64 Siswa</strong>
                    </li>
                    <li style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid var(--gray-200);">
                        <span><i class="fa-solid fa-calculator" style="color: var(--secondary-dark); width: 25px;"></i> Akuntansi (2 Kelas)</span>
                        <strong style="color: var(--primary);">64 Siswa</strong>
                    </li>
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa-solid fa-store" style="color: var(--secondary-dark); width: 25px;"></i> Pemasaran (2 Kelas)</span>
                        <strong style="color: var(--primary);">64 Siswa</strong>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran -->
    <section class="section" style="background-color: var(--white); border-radius: var(--radius-lg); margin-top: 3rem; margin-bottom: 3rem; box-shadow: var(--shadow-sm);">
        <div class="section-header">
            <h2 class="section-title">Alur Pendaftaran Online</h2>
            <p class="section-desc">Ikuti langkah-langkah di bawah ini untuk mendaftarkan diri Anda sebagai calon siswa baru di SMK Mandiri Pontianak.</p>
        </div>

        <div class="flowchart">
            <div class="flow-step flow-step-left">
                <div class="flow-num">1</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Registrasi Akun</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Membuat akun calon siswa menggunakan email aktif melalui halaman pendaftaran.</p>
            </div>
            
            <div class="flow-step flow-step-right">
                <div class="flow-num">2</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Isi Data Pendaftaran</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Masuk ke sistem dan isi formulir data diri, data orang tua, serta memilih jurusan yang diminati.</p>
            </div>

            <div class="flow-step flow-step-left">
                <div class="flow-num">3</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Upload Dokumen Persyaratan</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Unggah berkas scan pendukung seperti Ijazah/SKL, Kartu Keluarga, Akta Kelahiran, dan KTP Orang Tua.</p>
            </div>

            <div class="flow-step flow-step-right">
                <div class="flow-num">4</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Pembayaran Administrasi</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Lakukan transfer biaya administrasi pendaftaran ke nomor rekening resmi sekolah dan upload bukti transfer.</p>
            </div>

            <div class="flow-step flow-step-left">
                <div class="flow-num">5</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Validasi & Verifikasi</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Panitia PPDB memverifikasi kelengkapan berkas serta keabsahan bukti pembayaran yang telah Anda kirim.</p>
            </div>

            <div class="flow-step flow-step-right">
                <div class="flow-num">6</div>
                <h4 class="card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Pengumuman & Cetak Kartu</h4>
                <p class="card-text" style="font-size: 0.8rem; line-height: 1.35;">Lihat status kelulusan Anda di dashboard. Jika diterima, Anda dapat langsung mengunduh/mencetak bukti pendaftaran resmi.</p>
            </div>
        </div>
    </section>

    <!-- Jurusan Impian -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Program Keahlian Unggulan</h2>
            <p class="section-desc">Kami menyediakan 3 pilihan jurusan yang memiliki prospek kerja tinggi di era industri kreatif dan digital.</p>
        </div>

        <div class="grid grid-3">
            <div class="card">
                <div class="card-icon"><i class="fa-solid fa-palette"></i></div>
                <h3 class="card-title">Animasi</h3>
                <p class="card-text">Mempelajari teknik pembuatan animasi 2D & 3D, pemodelan objek, storyboard, digital painting, dan videografi untuk memenuhi kebutuhan industri kreatif.</p>
            </div>

            <div class="card">
                <div class="card-icon"><i class="fa-solid fa-calculator"></i></div>
                <h3 class="card-title">Akuntansi</h3>
                <p class="card-text">Membekali siswa dengan pemahaman siklus akuntansi perusahaan jasa/dagang, perpajakan, komputer akuntansi (MYOB), manajemen keuangan, dan administrasi umum.</p>
            </div>

            <div class="card">
                <div class="card-icon"><i class="fa-solid fa-store"></i></div>
                <h3 class="card-title">Pemasaran</h3>
                <p class="card-text">Membekali siswa dengan keterampilan bisnis ritel, digital marketing, negosiasi bisnis, pengelolaan media sosial bisnis, serta administrasi penjualan.</p>
            </div>
        </div>
    </section>
@endsection
