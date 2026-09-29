@extends('layouts.app')

@section('title', 'Fasilitas Sekolah')

@section('content')
    <section class="hero" style="padding: 4rem 2rem;">
        <div class="hero-content">
            <h1 class="hero-title" style="font-size: 2.5rem;">Sarana & Fasilitas Sekolah</h1>
            <p class="hero-subtitle">Mendukung kenyamanan dan kelancaran proses praktek mandiri siswa melalui penyediaan sarana prasarana penunjang yang memadai.</p>
        </div>
    </section>

    <section class="section">
        <div class="grid grid-2" style="gap: 1.25rem;">
            <!-- Lab Komputer -->
            <div class="card" style="display: flex; gap: 1rem; align-items: flex-start; padding: 1.25rem;">
                <div class="card-icon" style="flex-shrink: 0; width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 0;"><i class="fa-solid fa-desktop"></i></div>
                <div>
                    <h3 class="card-title" style="font-size: 1.1rem; margin-bottom: 0.35rem;">Ruang Praktik Lab Komputer</h3>
                    <p class="card-text" style="font-size: 0.85rem; line-height: 1.45;">Laboratorium komputer berspesifikasi tinggi untuk praktik rendering animasi 3D, desain grafis, editing video, akuntansi, dan bisnis digital.</p>
                </div>
            </div>

            <!-- Ruang Kelas Nyaman -->
            <div class="card" style="display: flex; gap: 1rem; align-items: flex-start; padding: 1.25rem;">
                <div class="card-icon" style="flex-shrink: 0; background: rgba(16, 185, 129, 0.1); color: var(--success); width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 0;"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div>
                    <h3 class="card-title" style="font-size: 1.1rem; margin-bottom: 0.35rem;">Ruang Kelas Nyaman & Interaktif</h3>
                    <p class="card-text" style="font-size: 0.85rem; line-height: 1.45;">Ruang kelas teori kondusif yang dilengkapi proyektor multimedia, pencahayaan optimal, pendingin ruangan, serta meja kursi modular untuk diskusi kelompok.</p>
                </div>
            </div>

            <!-- Fasilitas Olahraga & Umum -->
            <div class="card" style="display: flex; gap: 1rem; align-items: flex-start; padding: 1.25rem;">
                <div class="card-icon" style="flex-shrink: 0; background: rgba(59, 130, 246, 0.1); color: var(--info); width: 45px; height: 45px; font-size: 1.25rem; margin-bottom: 0;"><i class="fa-solid fa-basketball"></i></div>
                <div>
                    <h3 class="card-title" style="font-size: 1.1rem; margin-bottom: 0.35rem;">Fasilitas Olahraga & Parkir Luas</h3>
                    <p class="card-text" style="font-size: 0.85rem; line-height: 1.45;">Fasilitas lapangan olahraga (basket, futsal, badminton), mushola, kantin sehat, toilet terpisah, serta area parkir aman terpantau CCTV.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
