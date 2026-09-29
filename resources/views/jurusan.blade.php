@extends('layouts.app')

@section('title', 'Program Keahlian')

@section('content')
    <section class="hero" style="padding: 4rem 2rem;">
        <div class="hero-content">
            <h1 class="hero-title" style="font-size: 2.5rem;">Program Keahlian (Jurusan)</h1>
            <p class="hero-subtitle">SMK Mandiri Pontianak menawarkan 3 pilihan keahlian vokasi berkualitas tinggi yang terintegrasi dengan dunia kerja.</p>
        </div>
    </section>

    <section class="section">
        <div class="grid grid-3" style="gap: 1.5rem;">
            <!-- Animasi -->
            <div class="card" style="border-top: 4px solid var(--secondary-dark); padding: 1.5rem; display: flex; flex-direction: column;">
                <div class="card-icon" style="background: rgba(0, 198, 255, 0.1); color: var(--secondary-dark); width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 1rem;"><i class="fa-solid fa-film"></i></div>
                <h3 class="card-title" style="font-size: 1.15rem; margin-bottom: 0.5rem;">Animasi</h3>
                <p class="card-text" style="flex-grow: 1; margin-bottom: 1rem; font-size: 0.85rem; line-height: 1.45;">Mempelajari keterampilan seni kreatif digital seperti animasi 2D & 3D, pembuatan game art, editing video, efek visual, dan storyboard.</p>
                <div style="background: var(--gray-100); padding: 0.75rem; border-radius: var(--radius-sm); font-size: 0.8rem; margin-bottom: 1rem;">
                    <strong style="color: var(--primary);">Peluang Karir:</strong>
                    <p style="margin-top: 0.2rem; color: var(--gray-600);">3D Modeler, 2D/3D Animator, Video Editor, Game Designer, Storyboard Artist.</p>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-200); padding-top: 0.75rem; font-size: 0.8rem;">
                    <span style="color: var(--gray-600);"><i class="fa-solid fa-user-group" style="margin-right: 0.25rem;"></i> 2 Kelas</span>
                    <strong style="color: var(--primary);">Kuota: 64 Siswa</strong>
                </div>
            </div>

            <!-- Akuntansi -->
            <div class="card" style="border-top: 4px solid var(--primary); padding: 1.5rem; display: flex; flex-direction: column;">
                <div class="card-icon" style="width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 1rem;"><i class="fa-solid fa-calculator"></i></div>
                <h3 class="card-title" style="font-size: 1.15rem; margin-bottom: 0.5rem;">Akuntansi & Keuangan Lembaga</h3>
                <p class="card-text" style="flex-grow: 1; margin-bottom: 1rem; font-size: 0.85rem; line-height: 1.45;">Membekali siswa dengan keahlian pengelolaan keuangan, akuntansi perusahaan/perpajakan, serta komputer akuntansi (MYOB & Spreadsheet).</p>
                <div style="background: var(--gray-100); padding: 0.75rem; border-radius: var(--radius-sm); font-size: 0.8rem; margin-bottom: 1rem;">
                    <strong style="color: var(--primary);">Peluang Karir:</strong>
                    <p style="margin-top: 0.2rem; color: var(--gray-600);">Staf Keuangan, Kasir Bank/Swasta, Account Officer, Staf Pajak, Wirausaha.</p>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-200); padding-top: 0.75rem; font-size: 0.8rem;">
                    <span style="color: var(--gray-600);"><i class="fa-solid fa-user-group" style="margin-right: 0.25rem;"></i> 2 Kelas</span>
                    <strong style="color: var(--primary);">Kuota: 64 Siswa</strong>
                </div>
            </div>

            <!-- Pemasaran -->
            <div class="card" style="border-top: 4px solid var(--warning); padding: 1.5rem; display: flex; flex-direction: column;">
                <div class="card-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 1rem;"><i class="fa-solid fa-bullhorn"></i></div>
                <h3 class="card-title" style="font-size: 1.15rem; margin-bottom: 0.5rem;">Bisnis Digital & Pemasaran</h3>
                <p class="card-text" style="flex-grow: 1; margin-bottom: 1rem; font-size: 0.85rem; line-height: 1.45;">Mempelajari strategi pemasaran digital (SEO, media sosial, e-commerce), komunikasi bisnis, dan manajemen operasional toko ritel.</p>
                <div style="background: var(--gray-100); padding: 0.75rem; border-radius: var(--radius-sm); font-size: 0.8rem; margin-bottom: 1rem;">
                    <strong style="color: var(--primary);">Peluang Karir:</strong>
                    <p style="margin-top: 0.2rem; color: var(--gray-600);">Digital Marketer, Social Media Specialist, Pramuniaga, Customer Service.</p>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-200); padding-top: 0.75rem; font-size: 0.8rem;">
                    <span style="color: var(--gray-600);"><i class="fa-solid fa-user-group" style="margin-right: 0.25rem;"></i> 2 Kelas</span>
                    <strong style="color: var(--primary);">Kuota: 64 Siswa</strong>
                </div>
            </div>
        </div>
    </section>
@endsection
