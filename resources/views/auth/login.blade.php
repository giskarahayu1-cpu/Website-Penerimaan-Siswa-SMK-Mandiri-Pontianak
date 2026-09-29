@extends('layouts.app')

@section('title', 'Masuk Portal PPDB')

@section('content')
    <div class="form-container">
        <h2 class="form-title">Selamat Datang</h2>
        <p class="form-desc">Masuk ke akun Anda untuk melanjutkan proses pendaftaran PPDB online.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                </div>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="padding-right: 2.75rem;">
                    <button type="button" id="togglePassword" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 0.25rem; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="padding: 0.75rem 1.5rem;">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--gray-600);">
            @if(\App\Models\Setting::get('pendaftaran_status', 'dibuka') === 'ditutup')
                <span class="badge badge-ditolak" style="font-size: 0.85rem; padding: 0.4rem 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-lock"></i> Periode Pendaftaran Online Ditutup</span>
            @else
                Belum memiliki akun calon siswa? <a href="{{ route('register') }}" style="color: var(--secondary-dark); font-weight: 600;">Daftar Akun Baru</a>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>
@endsection
