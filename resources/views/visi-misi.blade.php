@extends('layouts.app')

@section('title', 'Visi & Misi')

@section('content')
    <section class="hero" style="padding: 4rem 2rem;">
        <div class="hero-content">
            <h1 class="hero-title" style="font-size: 2.5rem;">Visi & Misi</h1>
            <p class="hero-subtitle">Melihat arah, komitmen, dan tujuan jangka panjang dari SMK Mandiri Pontianak dalam mencerdaskan bangsa.</p>
        </div>
    </section>

    <section class="section" style="max-width: 900px;">
        <div class="card" style="margin-bottom: 3rem; border-left: 5px solid var(--primary); padding: 3rem;">
            <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                <div style="font-size: 2.5rem; color: var(--primary); opacity: 0.8;"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <h2 style="color: var(--primary); font-size: 1.75rem; margin-bottom: 1rem; font-weight: 700;">Visi</h2>
                    <p style="color: var(--gray-600); font-size: 1.1rem; line-height: 1.8; font-style: italic;">
                        "Sekolah Menengah Kejurusan (SMK) Mandiri Pontianak sebagai lembaga pendidikan dan latihan (Diklat) yang mendidik SDM intelektual dan Tenaga Kerja profesioanal pada dunia usaha dan industri secara madiri dan wiraswasta."
                    </p>
                </div>
            </div>
        </div>

        <div class="card" style="border-left: 5px solid var(--secondary-dark); padding: 3rem;">
            <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                <div style="font-size: 2.5rem; color: var(--secondary-dark); opacity: 0.8;"><i class="fa-solid fa-bullseye"></i></div>
                <div>
                    <h2 style="color: var(--primary); font-size: 1.75rem; margin-bottom: 1.5rem; font-weight: 700;">Misi</h2>
                    <ul style="display: flex; flex-direction: column; gap: 1rem; color: var(--gray-600);">
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <span style="background: var(--gray-100); color: var(--secondary-dark); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">1</span>
                            <span>Mendidik, melatih serta membimbing berbagai ilmu pengetahuan di bidang bisnis manajemen dan informatika di dunia usaha dan industri.</span>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <span style="background: var(--gray-100); color: var(--secondary-dark); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">2</span>
                            <span>Menciptakan belajar mengajar yang kondusif serta berwawasan mutu dan keunggulan di sekolah maupun dunia usaha dan industri berdasarkan norma-norma kesopanan.</span>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <span style="background: var(--gray-100); color: var(--secondary-dark); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">3</span>
                            <span>menghasilkan kwalitas tamatan yang berkepribadian, berpengalaman serta memiliki keterampilan yang tangguh untuk menghadapi perkembangan zaman.</span>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <span style="background: var(--gray-100); color: var(--secondary-dark); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">4</span>
                            <span>Menciptakan tamatan-tamatan profesional yang siap memasuki dunia usaha dan dunia industri sesuai dengan perkembangan teknologi yang maju.</span>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <span style="background: var(--gray-100); color: var(--secondary-dark); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">5</span>
                            <span>sekolah sebagai kebanggaan masyarakat mempersiapkan tenaga kerja yang handal dan berwibawa.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
