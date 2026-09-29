@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
    <section class="hero" style="padding: 4rem 2rem;">
        <div class="hero-content">
            <h1 class="hero-title" style="font-size: 2.5rem;">Profil Sekolah</h1>
            <p class="hero-subtitle">Mengenal lebih dekat SMK Swasta Mandiri Pontianak yang berdedikasi menghasilkan generasi berkarakter sejak tahun 2002.</p>
        </div>
    </section>

    <section class="section">
        <div class="profile-grid">
            <div>
                <!-- Mock School Image using placeholder or CSS pattern, since we want a nice design -->
                <div style="background: linear-gradient(135deg, var(--primary), var(--secondary-dark)); height: 350px; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; box-shadow: var(--shadow-lg); border: 8px solid white;">
                    <div style="text-align: center;">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo SMK Mandiri" style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1.5rem; background: white; padding: 5px; box-shadow: var(--shadow-sm); object-fit: contain;">
                        <h2>SMK Mandiri Pontianak</h2>
                        <p style="font-weight: 300;">Pontianak Timur, Kalimantan Barat</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 style="color: var(--primary); font-size: 1.75rem; margin-bottom: 1rem; font-weight: 700;">Tentang SMK Mandiri Pontianak</h2>
                <p style="color: var(--gray-600); margin-bottom: 1rem; text-align: justify; text-justify: inter-word; line-height: 1.6;">Sekolah Menengah Kejuruan (SMK) Swasta Mandiri Pontianak yang beralamat di Jalan Tanjung Raya II Gang SAMI Sumping, Kecamatan Pontianak Timur, Kota Pontianak, Kalimantan Barat, merupakan salah satu instansi pendidikan vokasi yang telah berdiri kokoh sejak tahun 2002.</p>
                <p style="color: var(--gray-600); margin-bottom: 1rem; text-align: justify; text-justify: inter-word; line-height: 1.6;">Sekolah ini berkomitmen memberikan pendidikan berkualitas, membentuk lulusan yang kompeten di bidangnya, serta menanamkan nilai-nilai karakter disiplin, mandiri, dan tanggung jawab. Melalui integrasi kurikulum industri dan fasilitas praktis, SMK Mandiri siap melatih siswa untuk langsung memasuki dunia kerja (vokasional) maupun melanjutkan studi ke jenjang yang lebih tinggi.</p>
                
                <h3 style="color: var(--gray-800); font-size: 1.25rem; margin-top: 2rem; margin-bottom: 1rem; font-weight: 700;">Identitas Sekolah</h3>
                <table class="info-table">
                    <tr>
                        <td>Nama Sekolah</td>
                        <td>SMK Swasta Mandiri Pontianak</td>
                    </tr>
                    <tr>
                        <td>NPSN / Akreditasi</td>
                        <td>320105218 / Terakreditasi B</td>
                    </tr>
                    <tr>
                        <td>Tahun Berdiri</td>
                        <td>2002</td>
                    </tr>
                    <tr>
                        <td>Alamat Lengkap</td>
                        <td>Jl. Tanjung Raya II Gang SAMI Sumping, Kec. Pontianak Timur, Kota Pontianak, Kalimantan Barat</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
@endsection
