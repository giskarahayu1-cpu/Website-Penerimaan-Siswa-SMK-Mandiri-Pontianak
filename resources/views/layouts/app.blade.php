<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB') - SMK Mandiri Pontianak</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1.0.4">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <header class="navbar">
        <a href="{{ route('home') }}" class="brand-container">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-logo" style="background: white; border-radius: 50%; padding: 2px; object-fit: contain;">
            <div>
                <span class="brand-title">SMK Mandiri Pontianak</span>
                <span class="brand-subtitle">PPDB Online Portal</span>
            </div>
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('about') }}" class="nav-link {{ Route::is('about') ? 'active' : '' }}">Profil</a>
            <a href="{{ route('visi-misi') }}" class="nav-link {{ Route::is('visi-misi') ? 'active' : '' }}">Visi Misi</a>
            <a href="{{ route('jurusan') }}" class="nav-link {{ Route::is('jurusan') ? 'active' : '' }}">Jurusan</a>
            <a href="{{ route('fasilitas') }}" class="nav-link {{ Route::is('fasilitas') ? 'active' : '' }}">Fasilitas</a>
            
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard Admin</a>
                @elseif(auth()->user()->role === 'kepsek')
                    <a href="{{ route('kepsek.dashboard') }}" class="nav-link">Dashboard Kepala Sekolah</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="nav-link">Dashboard Siswa</a>
                @endif
            @endauth
        </nav>

        <div class="auth-btn-group">
            @auth
                @if(auth()->user()->role === 'siswa')
                    @php
                        $siswa = auth()->user()->calonSiswa;
                        $notifications = collect();
                        $unreadCount = 0;
                        if ($siswa && $siswa->pendaftaran) {
                            $notifications = $siswa->pendaftaran->pembayaran()->orderBy('created_at', 'desc')->get();
                            $unreadCount = $siswa->pendaftaran->pembayaran()->where('is_read', false)->count();
                        }
                    @endphp
                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown-container">
                        <button class="notification-trigger" id="notificationTrigger" aria-label="Notifikasi">
                            <i class="fa-solid fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="notification-badge" id="notificationBadge">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        
                        <div class="notification-dropdown-menu" id="notificationDropdownMenu">
                            <div class="notification-dropdown-header">
                                <span>Notifikasi Pembayaran</span>
                                @if($unreadCount > 0)
                                    <span class="unread-mark-hint" id="unreadMarkHint">{{ $unreadCount }} belum dibaca</span>
                                @endif
                            </div>
                            <div class="notification-dropdown-list">
                                @forelse($notifications as $notif)
                                    <div class="notification-item {{ !$notif->is_read ? 'unread' : '' }}">
                                        <div class="notif-icon-status {{ $notif->status }}">
                                            @if($notif->status === 'valid')
                                                <i class="fa-solid fa-circle-check"></i>
                                            @elseif($notif->status === 'ditolak')
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @else
                                                <i class="fa-solid fa-clock"></i>
                                            @endif
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title">
                                                Pembayaran Angsuran Ke-{{ $notif->angsuran_ke }}
                                                <span class="status-badge {{ $notif->status }}">{{ ucfirst($notif->status) }}</span>
                                            </div>
                                            <div class="notif-details">
                                                Nominal: <strong>Rp {{ number_format($notif->jumlah, 0, ',', '.') }}</strong>
                                            </div>
                                            <div class="notif-details">
                                                No. Bayar: #{{ $notif->id_pembayaran }}
                                            </div>
                                            <div class="notif-time">
                                                {{ \Carbon\Carbon::parse($notif->tanggal_bayar)->format('d M Y') }} - {{ \Carbon\Carbon::parse($notif->created_at)->format('H:i') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="notification-empty">
                                        <i class="fa-solid fa-bell-slash"></i>
                                        <p>Belum ada riwayat pembayaran.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'admin')
                    @php
                        $adminPendingCount = \App\Models\Pembayaran::where('status', 'menunggu')->count();
                        $adminNotifications = \App\Models\Pembayaran::with('pendaftaran.calonSiswa')
                            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 WHEN status = 'valid' THEN 1 ELSE 2 END")
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp
                    <!-- Admin Notification Dropdown -->
                    <div class="notification-dropdown-container">
                        <button class="notification-trigger" id="adminNotificationTrigger" aria-label="Notifikasi">
                            <i class="fa-solid fa-bell"></i>
                            @if($adminPendingCount > 0)
                                <span class="notification-badge">{{ $adminPendingCount }}</span>
                            @endif
                        </button>
                        
                        <div class="notification-dropdown-menu" id="adminNotificationDropdownMenu" style="width: 350px;">
                            <div class="notification-dropdown-header">
                                <span>Pembayaran Masuk</span>
                                @if($adminPendingCount > 0)
                                    <span class="unread-mark-hint" style="background: rgba(245, 158, 11, 0.15); color: var(--warning);">{{ $adminPendingCount }} pending</span>
                                @else
                                    <span class="unread-mark-hint" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">Semua Terverifikasi</span>
                                @endif
                            </div>
                            <div class="notification-dropdown-list">
                                @forelse($adminNotifications as $notif)
                                    <a href="{{ route('admin.payments.show', $notif->id_pembayaran) }}" style="text-decoration: none; color: inherit; display: block;">
                                        <div class="notification-item {{ $notif->status === 'menunggu' ? 'unread' : '' }}">
                                            <div class="notif-icon-status {{ $notif->status }}">
                                                @if($notif->status === 'valid')
                                                    <i class="fa-solid fa-circle-check"></i>
                                                @elseif($notif->status === 'ditolak')
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                @else
                                                    <i class="fa-solid fa-clock"></i>
                                                @endif
                                            </div>
                                            <div class="notif-content">
                                                <div class="notif-title">
                                                    {{ $notif->pendaftaran->calonSiswa->nama ?? 'Siswa' }}
                                                    <span class="status-badge {{ $notif->status }}">{{ ucfirst($notif->status) }}</span>
                                                </div>
                                                <div class="notif-details">
                                                    Pembayaran Ke-{{ $notif->angsuran_ke }} sebesar <strong>Rp {{ number_format($notif->jumlah, 0, ',', '.') }}</strong>
                                                </div>
                                                <div class="notif-time">
                                                    {{ \Carbon\Carbon::parse($notif->tanggal_bayar ?? $notif->created_at)->format('d M Y') }} - {{ \Carbon\Carbon::parse($notif->created_at)->format('H:i') }} WIB
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="notification-empty">
                                        <i class="fa-solid fa-bell-slash"></i>
                                        <p>Belum ada riwayat transaksi pembayaran.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div style="padding: 0.75rem; border-top: 1px solid var(--gray-100); text-align: center; background: var(--gray-100);">
                                <a href="{{ route('admin.payments.index') }}" style="font-size: 0.8rem; color: var(--primary); font-weight: 600; text-decoration: none;">Lihat Semua Transaksi</a>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-accent btn-sm">Daftar</a>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        <!-- Success / Error Alerts -->
        @if(session('success') || session('error') || session('info') || $errors->any())
            <div class="section" style="padding-top: 2rem; padding-bottom: 0;">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            @foreach($errors->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: var(--dark); padding: 2rem 2rem 1.5rem; color: #cbd5e1; border-top: 3px solid #dfad33; font-size: 0.8rem; margin-top: auto; font-family: var(--font);">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 1.5rem;">
            <!-- Left: School Info -->
            <div>
                <h4 style="color: #dfad33; font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem;">SMK Mandiri Pontianak</h4>
                <p style="color: #94a3b8; line-height: 1.5; margin-bottom: 0.75rem; font-size: 0.8rem;">Mencetak lulusan yang cerdas, kompeten, berkarakter, serta siap memasuki dunia kerja maupun berwirausaha sejak tahun 2002.</p>
                <p style="color: #cbd5e1; font-size: 0.8rem; line-height: 1.5; margin: 0;">
                    <i class="fa-solid fa-location-dot" style="margin-right: 0.35rem; color: #22c55e;"></i> Jalan Tanjung Raya II Gang SAMI Sumping, Kec. Pontianak Timur, Kota Pontianak, Kalimantan Barat.
                </p>
            </div>
            
            <!-- Center: Link Terkait -->
            <div>
                <h4 style="color: var(--white); font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem;">Link Terkait</h4>
                <ul style="display: flex; flex-direction: column; gap: 0.35rem; padding: 0; margin: 0; list-style: none;">
                    <li><a href="{{ route('about') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: var(--transition);" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='#94a3b8'">Profil Sekolah</a></li>
                    <li><a href="{{ route('visi-misi') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: var(--transition);" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='#94a3b8'">Visi & Misi</a></li>
                    <li><a href="{{ route('jurusan') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: var(--transition);" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='#94a3b8'">Program Keahlian</a></li>
                    <li><a href="{{ route('fasilitas') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: var(--transition);" onmouseover="this.style.color='var(--secondary)'" onmouseout="this.style.color='#94a3b8'">Sarana & Fasilitas</a></li>
                </ul>
            </div>

            <!-- Right: Hubungi Kami -->
            <div>
                <h4 style="color: var(--white); font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem;">Hubungi Kami</h4>
                <p style="color: #94a3b8; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0.5rem;">Butuh bantuan terkait pendaftaran PPDB Online? Hubungi panitia pelaksana:</p>
                <div style="display: flex; flex-direction: column; gap: 0.35rem; font-size: 0.8rem; color: #cbd5e1;">
                    <span><i class="fa-solid fa-globe" style="color: #22c55e; margin-right: 0.35rem; width: 14px;"></i> www.smkmandiripontianak.sch.id</span>
                    <span><i class="fa-brands fa-instagram" style="color: #22c55e; margin-right: 0.35rem; width: 14px;"></i> @smkmandiripontianak</span>
                </div>
            </div>
        </div>
        
        <!-- Bottom Copyright Bar -->
        <div style="max-width: 1200px; margin: 0 auto; border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 0.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; font-size: 0.75rem; color: #64748b;">
            <p style="margin: 0;">&copy; 2026 SMK Mandiri Pontianak. All Rights Reserved.</p>
            <p style="margin: 0; text-align: right;">Dikembangkan oleh Giska Rahayu (NIM. 3202316144)</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Automatically uppercase inputs with class 'uppercase-input'
        const uppercaseInputs = document.querySelectorAll('.uppercase-input');
        uppercaseInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
            
            // Uppercase initial value if present
            if (input.value) {
                input.value = input.value.toUpperCase();
            }
        });

        // Notification dropdown toggle
        const trigger = document.getElementById('notificationTrigger');
        const menu = document.getElementById('notificationDropdownMenu');
        const badge = document.getElementById('notificationBadge');
        const hint = document.getElementById('unreadMarkHint');
        
        if (trigger && menu) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('active');
                
                // If opening the dropdown and there is an unread count, mark as read
                if (menu.classList.contains('active') && (badge || document.querySelector('.notification-item.unread'))) {
                    // Send AJAX post request to mark as read
                    fetch('{{ route("student.notifications.read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove badge and hint
                            if (badge) badge.remove();
                            if (hint) hint.remove();
                            // Mark item elements as read visually
                            document.querySelectorAll('.notification-item.unread').forEach(function(item) {
                                item.classList.remove('unread');
                            });
                        }
                    })
                    .catch(err => console.error('Error marking notifications as read:', err));
                }
            });
            
            // Click outside to close
            document.addEventListener('click', function(e) {
                if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('active');
                }
            });
        }

        // Admin Notification dropdown toggle
        const adminTrigger = document.getElementById('adminNotificationTrigger');
        const adminMenu = document.getElementById('adminNotificationDropdownMenu');
        
        if (adminTrigger && adminMenu) {
            adminTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                adminMenu.classList.toggle('active');
            });
            
            document.addEventListener('click', function(e) {
                if (!adminTrigger.contains(e.target) && !adminMenu.contains(e.target)) {
                    adminMenu.classList.remove('active');
                }
            });
        }
    });
    </script>

    @yield('scripts')
</body>
</html>
