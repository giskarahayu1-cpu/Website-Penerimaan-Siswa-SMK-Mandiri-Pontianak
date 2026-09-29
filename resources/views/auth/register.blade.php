@extends('layouts.app')

@section('title', 'Daftar Akun PPDB')

@section('content')
    <div class="form-container">
        <h2 class="form-title">Registrasi Akun Calon Siswa</h2>
        <p class="form-desc">Buat akun menggunakan email aktif untuk mengisi formulir pendaftaran online.</p>

        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control uppercase-input" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" id="nisn" class="form-control" placeholder="Masukkan 10 digit NISN" value="{{ old('nisn') }}" required minlength="10" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                @error('nisn')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_hp" class="form-label">Nomor Handphone / WA</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan nomor handphone aktif" value="{{ old('no_hp') }}" required maxlength="15" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                @error('no_hp')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required oninvalid="this.setCustomValidity('password wajib diisi.')" oninput="this.setCustomValidity('')" style="padding-right: 2.75rem;">
                    <button type="button" id="togglePassword" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 0.25rem; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="eyeIconPassword"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required style="padding-right: 2.75rem;">
                    <button type="button" id="toggleConfirmPassword" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 0.25rem; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-accent btn-block" style="padding: 0.75rem 1.5rem;">
                <i class="fa-solid fa-user-plus"></i> Daftar Akun Baru
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--gray-600);">
            Sudah memiliki akun? <a href="{{ route('login') }}" style="color: var(--secondary-dark); font-weight: 600;">Masuk di sini</a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIconPassword = document.querySelector('#eyeIconPassword');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        if (type === 'password') {
            eyeIconPassword.classList.remove('fa-eye-slash');
            eyeIconPassword.classList.add('fa-eye');
        } else {
            eyeIconPassword.classList.remove('fa-eye');
            eyeIconPassword.classList.add('fa-eye-slash');
        }
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const passwordConfirm = document.querySelector('#password_confirmation');
    const eyeIconConfirm = document.querySelector('#eyeIconConfirm');

    toggleConfirmPassword.addEventListener('click', function (e) {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        if (type === 'password') {
            eyeIconConfirm.classList.remove('fa-eye-slash');
            eyeIconConfirm.classList.add('fa-eye');
        } else {
            eyeIconConfirm.classList.remove('fa-eye');
            eyeIconConfirm.classList.add('fa-eye-slash');
        }
    });
</script>
@endsection
