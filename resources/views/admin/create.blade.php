@extends('layouts.app')

@section('title', 'Tambah Calon Siswa')

@section('content')
<div class="dashboard-container">
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link active">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-money-bill-wave"></i> Verifikasi Pembayaran
                </a>
                <a href="{{ route('admin.export') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </a>
            </nav>
        </div>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header" style="margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary); font-size: 1.75rem;">Registrasi Calon Siswa Baru</h1>
                <p style="color: var(--gray-600);">Tambah data calon siswa baru secara manual ke dalam sistem.</p>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card" style="max-width: 750px; margin: 0 auto; padding: 2.5rem;">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <h3 style="color: var(--primary); border-bottom: 1.5px solid var(--gray-200); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">Kredensial Akun Login</h3>
                
                <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Pengguna (Username)</label>
                        <input type="text" name="name" id="name" class="form-control uppercase-input" placeholder="Nama lengkap user" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Aktif</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="siswa@email.com" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="password" class="form-label">Password Sementara</label>
                    <input type="text" name="password" id="password" class="form-control" placeholder="Masukkan password untuk login siswa" value="{{ old('password', Str::random(10)) }}" required>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <h3 style="color: var(--primary); border-bottom: 1.5px solid var(--gray-200); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">Detail Profil Siswa</h3>
                
                <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" name="nisn" id="nisn" class="form-control" placeholder="10 digit NISN" value="{{ old('nisn') }}" required minlength="10" maxlength="10">
                        @error('nisn')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_hp" class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Contoh: 08123456789" value="{{ old('no_hp') }}" required>
                        @error('no_hp')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jurusan" class="form-label">Program Keahlian</label>
                        <select name="jurusan" id="jurusan" class="form-control" required>
                            <option value="" disabled selected>Pilih Jurusan</option>
                            <option value="Animasi" {{ old('jurusan') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                            <option value="Akuntansi" {{ old('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                            <option value="Pemasaran" {{ old('jurusan') == 'Pemasaran' ? 'selected' : '' }}>Pemasaran</option>
                        </select>
                        @error('jurusan')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat lengkap calon siswa..." required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 0.75rem;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Calon Siswa
                </button>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
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
});
</script>
@endsection
