<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu PPDB - {{ $siswa->nama }}</title>
    <!-- Custom styling inside for absolute consistency when printing -->
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background-color: #fff;
        }
        .print-container {
            border: 3px double #000;
            padding: 25px;
            max-width: 650px;
            margin: 0 auto;
        }
        .header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-box {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 20px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 1.4rem;
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
        }
        .header-text p {
            margin: 4px 0 0 0;
            font-size: 0.8rem;
        }
        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }
        .title-section h3 {
            margin: 0;
            font-size: 1.15rem;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title-section span {
            font-size: 0.9rem;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 6px 0;
            font-size: 0.95rem;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 35%;
            font-weight: bold;
        }
        .info-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }
        .status-box {
            border: 2px solid #000;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 1.15rem;
            margin-bottom: 30px;
            background-color: #f5f5f5;
        }
        .sign-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-space {
            height: 60px;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: #0f172a;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9rem;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .print-container {
                max-width: 100%;
                border: 3px double #000;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Cetak Kartu PPDB</button>
    </div>

    <div class="print-container">
        <div class="header-section">
            <img src="{{ asset('img/logo.png') }}" class="logo-box" alt="Logo">
            <div class="header-text">
                <h2>SMK Swasta Mandiri Pontianak</h2>
                <p>Jalan Tanjung Raya II Gang SAMI Sumping, Kec. Pontianak Timur, Kota Pontianak, Kalbar</p>
                <p>Email: info@smkmandiriptk.sch.id | Website: www.smkmandiriptk.sch.id</p>
            </div>
        </div>

        <div class="title-section">
            <h3>Bukti Pendaftaran & Kelulusan PPDB</h3>
            <span>No. Pendaftaran: REG-{{ str_pad($pendaftaran->id_daftar, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        <table class="info-table">
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{ $siswa->nisn }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $siswa->nama }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Pilihan Jurusan</td>
                <td>:</td>
                <td><strong>{{ $siswa->jurusan }}</strong></td>
            </tr>
            <tr>
                <td>No HP / WhatsApp</td>
                <td>:</td>
                <td>{{ $siswa->no_hp }}</td>
            </tr>
            <tr>
                <td>Alamat Tinggal</td>
                <td>:</td>
                <td>{{ $siswa->alamat }}</td>
            </tr>
            <tr>
                <td>Tanggal Registrasi</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d-F-Y') }}</td>
            </tr>
        </table>

        <div class="status-box">
            Status Kelulusan: Diterima
        </div>

        <div class="sign-section">
            <div>
                <p>Calon Peserta Didik,</p>
                <div class="sign-space"></div>
                <p><strong>{{ $siswa->nama }}</strong></p>
            </div>
            <div class="sign-box">
                <p>Pontianak, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                <p>Koordinator Tugas Akhir/PPDB,</p>
                <div class="sign-space"></div>
                <p><strong>Drs. H. MAHDI, M.T., M.M.Pd.</strong></p>
                <p style="font-size: 0.8rem; margin: 0;">NIP. 196202091990111001</p>
            </div>
        </div>
    </div>

    <script>
        // Auto trigger print layout when loaded
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
