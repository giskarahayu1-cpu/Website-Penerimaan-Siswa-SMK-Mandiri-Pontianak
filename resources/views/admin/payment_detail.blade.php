@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <h4 class="sidebar-title">Menu Utama</h4>
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                    <i class="fa-solid fa-chart-pie"></i> Ringkasan
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-users"></i> Data Pendaftar
                </a>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link active">
                    <i class="fa-solid fa-money-bill-wave"></i> Verifikasi Pembayaran
                </a>
                <a href="{{ route('admin.export') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="dashboard-content">
        <!-- Breadcrumbs and Header -->
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--primary); font-size: 1.75rem; margin-bottom: 0.25rem; font-weight: 700;">Detail Pembayaran</h1>
            <div class="breadcrumbs" style="font-size: 0.85rem; color: var(--gray-400); display: flex; gap: 0.5rem; align-items: center;">
                <a href="{{ route('admin.dashboard') }}" style="color: var(--gray-600); text-decoration: none;">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.payments.index') }}" style="color: var(--gray-600); text-decoration: none;">Verifikasi Pembayaran</a>
                <span>/</span>
                <span style="color: var(--primary); font-weight: 600;">Detail</span>
            </div>
        </div>

        @php
            $months = [
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            $date = \Carbon\Carbon::parse($payment->tanggal_bayar);
            $formattedDate = $date->format('d') . ' ' . $months[$date->month] . ' ' . $date->format('Y');
            $timePart = $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('H:i') : '00:00';

            $totalBiaya = 3100000;
            $sudahDibayar = $pembayaran ? $pembayaran->whereIn('status', ['valid', 'menunggu'])->sum('jumlah') : 0;
            $sisaBayar = max(0, $totalBiaya - $sudahDibayar);
            $hasPending = $pembayaran ? $pembayaran->where('status', 'menunggu')->count() > 0 : false;
            
            $statusText = 'Belum Lunas';
            $statusColor = 'var(--danger)';
            $statusBg = '#fff5f5';
            $statusBorder = '1px solid rgba(239, 68, 68, 0.2)';
            
            if ($sisaBayar == 0 && !$hasPending) {
                $statusText = 'Lunas';
                $statusColor = 'var(--success)';
                $statusBg = 'rgba(16, 185, 129, 0.1)';
                $statusBorder = '1.5px solid rgba(16, 185, 129, 0.2)';
            } elseif ($hasPending) {
                $statusText = 'Menunggu Verifikasi';
                $statusColor = 'var(--info)';
                $statusBg = 'rgba(59, 130, 246, 0.1)';
                $statusBorder = '1.5px solid rgba(59, 130, 246, 0.2)';
            } elseif ($sudahDibayar > 0) {
                $statusText = 'Belum Lunas (Cicilan)';
                $statusColor = 'var(--warning)';
                $statusBg = 'rgba(245, 158, 11, 0.1)';
                $statusBorder = '1.5px solid rgba(245, 158, 11, 0.2)';
            }
        @endphp

        <!-- Detail Layout Grid: Top Section (Matching Mockup) -->
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Left Card: Detail Pembayaran Table -->
            <div class="card" style="padding: 2.5rem; border-radius: var(--radius-md); background: white; box-shadow: var(--shadow-sm);">
                <h3 style="color: var(--primary); font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-file-invoice-dollar" style="margin-right: 0.5rem;"></i> Informasi Transaksi Ini
                </h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; width: 35%; color: var(--gray-600); font-weight: 500;">Nama Siswa</td>
                        <td style="padding: 1rem 0; font-weight: 600; color: var(--gray-800);">: {{ $siswa->nama ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Pembayaran Ke</td>
                        <td style="padding: 1rem 0; font-weight: 600; color: var(--gray-800);">: {{ $payment->angsuran_ke }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Nominal</td>
                        <td style="padding: 1rem 0; font-weight: 600; color: var(--primary);">: Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Metode Pembayaran</td>
                        <td style="padding: 1rem 0; font-weight: 600; color: var(--gray-800);">: {{ $payment->metode_pembayaran === 'transfer' ? 'Transfer Bank' : 'Tunai' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Tanggal Transfer</td>
                        <td style="padding: 1rem 0; font-weight: 600; color: var(--gray-800);">: {{ $formattedDate }} {{ $timePart }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Status</td>
                        <td style="padding: 1rem 0;">: 
                            @if($payment->status === 'menunggu')
                                <span style="background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; padding: 0.35rem 0.85rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem;">Menunggu Verifikasi</span>
                            @elseif($payment->status === 'valid')
                                <span style="background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; padding: 0.35rem 0.85rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem;">Telah Valid</span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; padding: 0.35rem 0.85rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem;">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem 0; color: var(--gray-600); font-weight: 500;">Keterangan</td>
                        <td style="padding: 1rem 0; color: var(--gray-800);">: -</td>
                    </tr>
                </table>
            </div>

            <!-- Right Card: Bukti Pembayaran Preview -->
            <div class="card" style="padding: 2.5rem; border-radius: var(--radius-md); background: white; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                <h3 style="align-self: flex-start; color: var(--gray-800); font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem;">Bukti Pembayaran</h3>
                
                <div style="width: 100%; height: 260px; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; background: #f8fafc; padding: 1rem; margin-bottom: 1.5rem; overflow: hidden;">
                    @if($payment->bukti_bayar)
                        <img src="{{ asset('storage/' . $payment->bukti_bayar) }}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 4px;">
                    @else
                        <div style="color: var(--gray-400); text-align: center; padding: 2rem;">
                            <i class="fa-solid fa-image-slash" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Tidak ada bukti gambar
                        </div>
                    @endif
                </div>

                @if($payment->bukti_bayar)
                    <a href="{{ asset('storage/' . $payment->bukti_bayar) }}" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; max-width: 220px; display: flex; justify-content: center; align-items: center; gap: 0.5rem; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Lihat Lebih Besar
                    </a>
                @endif
            </div>
        </div>

        <!-- Action buttons for current payment -->
        <div style="display: flex; gap: 1rem; align-items: center; background: white; padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
            @if($payment->status === 'menunggu')
                <form action="{{ route('admin.payments.verify', $payment->id_pembayaran) }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <input type="hidden" name="status" value="valid">
                    <button type="submit" class="btn" style="background: #10b981; color: white; display: flex; align-items: center; gap: 0.5rem; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                        <i class="fa-solid fa-check"></i> Validasi Pembayaran
                    </button>
                </form>
                
                <form action="{{ route('admin.payments.verify', $payment->id_pembayaran) }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="btn" style="background: #ef4444; color: white; display: flex; align-items: center; gap: 0.5rem; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                        <i class="fa-solid fa-xmark"></i> Tolak Pembayaran
                    </button>
                </form>
            @endif
            
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.9rem; border: 1px solid var(--gray-200); background: var(--gray-100); text-decoration: none; color: var(--gray-800); transition: background 0.2s;" onmouseover="this.style.background='var(--gray-200)'" onmouseout="this.style.background='var(--gray-100)'">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>

            @if($siswa)
                <a href="{{ route('admin.students.show', $siswa->id_siswa) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.9rem; border: 1px solid var(--gray-200); background: var(--gray-100); text-decoration: none; color: var(--primary); margin-left: auto;">
                    <i class="fa-solid fa-user-graduate"></i> Lihat Profil Lengkap Siswa
                </a>
            @endif
        </div>

        <!-- Bottom Section: Status Angsuran & Fitur Edit Angsuran -->
        @if($siswa)
        <div class="card" style="padding: 2.5rem; border-radius: var(--radius-md); background: white; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
            <h3 style="color: var(--primary); font-size: 1.25rem; font-weight: 700; border-bottom: 2px solid var(--gray-200); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-receipt" style="margin-right: 0.5rem;"></i> Status Pembayaran & Riwayat Angsuran Siswa
            </h3>

            <!-- Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div style="border: 1px solid var(--gray-200); padding: 0.75rem 1rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                    <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Total Tagihan</span>
                    <strong style="font-size: 1.1rem; color: var(--primary);">Rp 3.100.000</strong>
                </div>
                <div style="border: 1px solid var(--gray-200); padding: 0.75rem 1rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                    <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Total Dibayar</span>
                    <strong style="font-size: 1.1rem; color: var(--success);">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</strong>
                </div>
                <div style="border: 1px solid var(--gray-200); padding: 0.75rem 1rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
                    <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Sisa Pembayaran</span>
                    <strong style="font-size: 1.1rem; color: var(--danger);">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</strong>
                </div>
                <div style="background: {{ $statusBg }}; border: {{ $statusBorder }}; padding: 0.75rem 1rem; border-radius: var(--radius-sm); display: flex; flex-direction: column; justify-content: center;">
                    <span style="font-size: 0.75rem; color: var(--gray-600); font-weight: 600;">Status</span>
                    <strong style="font-size: 0.95rem; color: {{ $statusColor }};">{{ $statusText }}</strong>
                </div>
            </div>

            <!-- Installment Slots Display for Admin -->
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--gray-800); margin-bottom: 1rem;">Status 4 Slot Angsuran</h4>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
                @for($i = 1; $i <= 4; $i++)
                    @php
                        $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                        $isCurrentSlot = ($payment->angsuran_ke == $i);
                    @endphp
                    <div style="border: {{ $isCurrentSlot ? '2px solid var(--secondary)' : '1px solid var(--gray-200)' }}; border-radius: var(--radius-sm); padding: 0.85rem; background: {{ $isCurrentSlot ? '#f0f9ff' : 'var(--white)' }}; position: relative;">
                        @if($isCurrentSlot)
                            <span style="position: absolute; top: -10px; right: 10px; background: var(--secondary); color: white; font-size: 0.6rem; font-weight: 700; padding: 2px 6px; border-radius: 10px; text-transform: uppercase;">Transaksi Ini</span>
                        @endif
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--gray-600); margin-bottom: 0.25rem;">Angsuran {{ $i }}</div>
                        @if($pembayaranAngsuran)
                            <div style="font-weight: bold; font-size: 1rem; color: var(--primary); margin-bottom: 0.25rem;">Rp {{ number_format($pembayaranAngsuran->jumlah, 0, ',', '.') }}</div>
                            <div style="margin-bottom: 0.25rem;">
                                <span class="badge badge-{{ $pembayaranAngsuran->status }}" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">
                                    {{ $pembayaranAngsuran->status }}
                                </span>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--gray-500);">Tgl: {{ \Carbon\Carbon::parse($pembayaranAngsuran->tanggal_bayar)->format('d-m-Y') }}</div>
                            <div style="font-size: 0.7rem; color: var(--gray-500); margin-top: 0.1rem;">
                                Metode: <strong style="text-transform: capitalize;">{{ $pembayaranAngsuran->metode_pembayaran === 'tunai' ? 'Tunai / Cash' : 'Transfer' }}</strong>
                            </div>
                            @if($pembayaranAngsuran->bukti_bayar)
                                <a href="{{ asset('storage/' . $pembayaranAngsuran->bukti_bayar) }}" target="_blank" style="display: block; font-size: 0.75rem; color: var(--secondary-dark); font-weight: 600; margin-top: 0.5rem; text-decoration: none;">Lihat Bukti</a>
                            @endif
                        @else
                            <div style="font-size: 0.8rem; color: var(--gray-400); font-style: italic; padding: 0.5rem 0;">Belum ada data</div>
                        @endif
                    </div>
                @endfor
            </div>

            <!-- Form Edit Angsuran -->
            <div style="border-top: 1.5px dashed var(--gray-200); padding-top: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--primary); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-pen-to-square"></i> Kelola / Edit Angsuran Pembayaran Siswa
                </h4>
                
                <form action="{{ route('admin.students.payments.save', $siswa->id_siswa) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label for="pembayaran_angsuran_ke" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Pilih Pembayaran Ke-</label>
                            <select name="angsuran_ke" id="pembayaran_angsuran_ke" class="form-control" required style="background: white;" onchange="loadInstallmentDetails(this.value)">
                                @for($i = 1; $i <= 4; $i++)
                                    @php
                                        $pembayaranAngsuran = $pembayaran ? $pembayaran->where('angsuran_ke', $i)->first() : null;
                                    @endphp
                                    @if($pembayaranAngsuran)
                                        @if($pembayaranAngsuran->status === 'valid')
                                            <option value="{{ $i }}" {{ $payment->angsuran_ke == $i ? 'selected' : '' }}>Pembayaran {{ $i }} (Valid)</option>
                                        @elseif($pembayaranAngsuran->status === 'menunggu')
                                            <option value="{{ $i }}" {{ $payment->angsuran_ke == $i ? 'selected' : '' }} style="color: #f59e0b;">Pembayaran {{ $i }} (Menunggu Verifikasi)</option>
                                        @elseif($pembayaranAngsuran->status === 'ditolak')
                                            <option value="{{ $i }}" {{ $payment->angsuran_ke == $i ? 'selected' : '' }}>Pembayaran {{ $i }} (Ditolak)</option>
                                        @endif
                                    @else
                                        <option value="{{ $i }}" {{ $payment->angsuran_ke == $i ? 'selected' : '' }}>Pembayaran {{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pembayaran_metode" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="pembayaran_metode" class="form-control" required style="background: white;">
                                <option value="transfer">Transfer Bank</option>
                                <option value="tunai">Tunai / Cash di Sekolah</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label for="pembayaran_jumlah" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Nominal Pembayaran (Rp)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <span style="position: absolute; left: 1rem; color: var(--gray-600); font-weight: 600; pointer-events: none; font-size: 0.9rem;">Rp</span>
                                <input type="text" name="jumlah" id="pembayaran_jumlah" class="form-control" required placeholder="Contoh: 775.000" style="padding-left: 2.5rem;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pembayaran_tanggal_bayar" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Tanggal Pembayaran</label>
                            <input type="date" name="tanggal_bayar" id="pembayaran_tanggal_bayar" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="pembayaran_status" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Status Verifikasi</label>
                            <select name="status" id="pembayaran_status" class="form-control" required style="background: white;">
                                <option value="valid">Valid (Lunas/Diterima)</option>
                                <option value="menunggu">Menunggu Verifikasi</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pembayaran_bukti_bayar" class="form-label" style="font-size: 0.85rem; font-weight: 600;">Lampiran Bukti Baru (Opsional)</label>
                            <input type="file" name="bukti_bayar" id="pembayaran_bukti_bayar" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="width: 100%; padding: 0.75rem; font-weight: 700; border: none; color: white; font-size: 0.95rem; cursor: pointer;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Angsuran
                    </button>
                </form>
            </div>
        </div>
        @endif
    </main>
</div>
@endsection

@section('scripts')
<script>
    const installmentData = @json($pembayaran ? $pembayaran->keyBy('angsuran_ke') : []);
    
    function formatRupiah(value) {
        if (!value) return '';
        let number = String(value).replace(/[^0-9]/g, '');
        return number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function loadInstallmentDetails(slot) {
        const data = installmentData[slot];
        const jumlahInput = document.getElementById('pembayaran_jumlah');
        const tanggalInput = document.getElementById('pembayaran_tanggal_bayar');
        const statusSelect = document.getElementById('pembayaran_status');
        const metodeSelect = document.getElementById('pembayaran_metode');
        
        if (!jumlahInput) return;

        // Reset to default editable status
        jumlahInput.readOnly = false;
        jumlahInput.style.backgroundColor = '';
        jumlahInput.style.cursor = '';
        if (metodeSelect) {
            metodeSelect.disabled = false;
        }

        if (data) {
            jumlahInput.value = formatRupiah(data.jumlah);
            tanggalInput.value = data.tanggal_bayar;
            statusSelect.value = data.status;
            if (metodeSelect) {
                metodeSelect.value = data.metode_pembayaran || 'transfer';
            }

            // Lock if student selected transfer bank
            if (data.metode_pembayaran === 'transfer') {
                if (metodeSelect) {
                    metodeSelect.value = 'transfer';
                    metodeSelect.disabled = true;
                }
                jumlahInput.readOnly = true;
                jumlahInput.style.backgroundColor = '#e2e8f0';
                jumlahInput.style.cursor = 'not-allowed';
            }
        } else {
            jumlahInput.value = '';
            tanggalInput.value = '{{ date('Y-m-d') }}';
            statusSelect.value = 'valid'; // default to valid for manual admin override
            if (metodeSelect) {
                metodeSelect.value = 'transfer';
            }
        }

        // Force Pembayaran 1 (slot 1) to always be 1.500.000 and readonly
        if (slot == 1) {
            jumlahInput.value = formatRupiah(1500000);
            jumlahInput.readOnly = true;
            jumlahInput.style.backgroundColor = '#e2e8f0';
            jumlahInput.style.cursor = 'not-allowed';
        }
    }
    
    // Run on init
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahInput = document.getElementById('pembayaran_jumlah');
        if (jumlahInput) {
            jumlahInput.addEventListener('input', function(e) {
                this.value = formatRupiah(this.value);
            });
            
            const form = jumlahInput.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    jumlahInput.value = jumlahInput.value.replace(/\./g, '');
                    const metodeSelect = document.getElementById('pembayaran_metode');
                    if (metodeSelect) {
                        metodeSelect.disabled = false;
                    }
                });
            }
        }
        
        const selectEl = document.getElementById('pembayaran_angsuran_ke');
        if (selectEl) {
            const currentSelectedSlot = selectEl.value || {{ $payment->angsuran_ke ?? 1 }};
            loadInstallmentDetails(currentSelectedSlot);
        }
    });
</script>
@endsection
