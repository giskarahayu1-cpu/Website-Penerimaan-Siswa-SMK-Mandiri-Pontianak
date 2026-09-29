<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PPDB SMK Mandiri Pontianak 2026</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background-color: #fff;
            padding: 20px;
        }
        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 15px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 3px 0 0 0;
            font-size: 10px;
        }
        .title {
            text-align: center;
            text-transform: uppercase;
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .report-table th {
            border: 1px solid #000;
            padding: 8px;
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
        }
        .report-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .sign-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }
        .sign-box {
            text-align: center;
            width: 250px;
        }
        .sign-space {
            height: 70px;
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
            font-size: 12px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Cetak Laporan PPDB</button>
    </div>

    <div class="header-container">
        <img src="{{ asset('img/logo.png') }}" class="logo" alt="Logo">
        <div class="header-text">
            <h2>SMK Swasta Mandiri Pontianak</h2>
            <p>Jalan Tanjung Raya II Gang SAMI Sumping, Kec. Pontianak Timur, Kota Pontianak, Kalbar</p>
            <p>Email: info@smkmandiriptk.sch.id | Website: www.smkmandiriptk.sch.id</p>
        </div>
    </div>

    <div class="title">
        Laporan Hasil Penerimaan Peserta Didik Baru (PPDB)<br>
        Tahun Ajaran 2026/2027
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">No. Daftar</th>
                <th style="width: 15%;">NISN</th>
                <th style="width: 20%;">Nama Lengkap</th>
                <th style="width: 8%; text-align: center;">L/P</th>
                <th style="width: 15%;">Program Keahlian</th>
                <th style="width: 12%;">No HP</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($students as $student)
                @php
                    $pendaftaran = $student->pendaftaran;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>REG-{{ str_pad($pendaftaran->id_daftar ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td><strong>{{ $student->nama }}</strong></td>
                    <td style="text-align: center;">{{ $student->jenis_kelamin }}</td>
                    <td>{{ $student->jurusan }}</td>
                    <td>{{ $student->no_hp }}</td>
                    <td>{{ ucfirst($pendaftaran->status ?? '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada pendaftar terdata di sistem.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="sign-section">
        <div class="sign-box">
            <p>Pontianak, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Koordinator Panitia PPDB,</p>
            <div class="sign-space"></div>
            <p><strong>Drs. H. MAHDI, M.T., M.M.Pd.</strong></p>
            <p style="font-size: 10px; margin: 0;">NIP. 196202091990111001</p>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
