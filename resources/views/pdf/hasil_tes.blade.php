<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Blanko Nilai Tes - {{ $santri->nama_lengkap }}</title>
    <style>
        @page { margin: 25px 40px; }
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            margin: 2px 0;
        }

        .header-subtitle {
            font-size: 11px;
            font-style: italic;
        }

        .header-address {
            font-size: 10px;
            color: #333;
            margin-top: 5px;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 5px 0 10px;
        }

        .content-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 20px 0 15px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
        }

        .info-table td:first-child {
            width: 140px;
            font-weight: bold;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .nilai-table th, .nilai-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
            text-align: center;
        }

        .nilai-table th {
            background-color: #eee;
        }

        .kriteria-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .kriteria-list {
            font-size: 10.5px;
            margin: 0 0 15px 18px;
            padding: 0;
        }

        .catatan-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 50px;
            font-size: 11px;
        }

        .catatan-title {
            font-weight: bold;
            font-style: italic;
            margin-bottom: 6px;
        }

        .signature-box {
            margin-top: 40px;
            width: 260px;
            float: right;
            text-align: center;
            font-size: 11px;
        }

        .penguji {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
   <table width="100%">
    <tr>
        <td style="width: 80px;">
            <img src="{{ public_path('images/aifaceswap-output (6).png') }}" style="max-width: 70px;" alt="Logo">
        </td>
        <td style="text-align: center;">
            <div style="font-weight: bold; font-size: 16px;">PANITIA PENERIMAAN SANTRI BARU</div>
            <div style="font-weight: bold; font-size: 16px;">PONDOK PESANTREN LA TANSA 2</div>
            <div style="font-style: italic; font-size: 11px;">"Berdiri di atas dan untuk semua golongan"</div>
            <div style="font-size: 9px; color: #555;">Pondok Pesantren La Tansa 2 Boarding School, Cibadak, Kec. Cibadak, Kabupaten Lebak, Banten 42357</div>
        </td>
        <td style="width: 80px;"></td> <!-- buat balance kanan -->
    </tr>
</table>
<hr>

    <div class="content-title">BLANKO NILAI TES</div>

    <table class="info-table">
        <tr><td>No. Pendaftaran</td><td>:</td><td>{{ $santri->nomor_pendaftaran }}</td></tr>
        <tr><td>Nama Lengkap</td><td>:</td><td>{{ $santri->nama_lengkap }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $santri->jenis_kelamin }}</td></tr>
        <tr><td>Lulusan</td><td>:</td><td>{{ $santri->asal_sekolah }}</td></tr>
    </table>

    <table class="nilai-table">
        <thead>
            <tr>
                <th colspan="6">NILAI</th>
            </tr>
            <tr>
                <th>Al-Qur'an</th>
                <th>B. Arab</th>
                <th>B. Inggris</th>
                <th>Matematika</th>
                <th>Interview</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $santri->hasilTes->nilai_alquran ?? '-' }}</td>
                <td>{{ $santri->hasilTes->nilai_arab ?? '-' }}</td>
                <td>{{ $santri->hasilTes->nilai_inggris ?? '-' }}</td>
                <td>{{ $santri->hasilTes->nilai_matematika ?? '-' }}</td>
                <td>{{ $santri->hasilTes->nilai_interview ?? '-' }}</td>
                <td><strong>{{ $santri->status_pendaftaran == 'lulus_seleksi' ? 'LULUS' : 'TIDAK LULUS' }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="kriteria-title">Kriteria Penilaian:</div>
    <ul class="kriteria-list">
        <li>A = Istimewa (Lulus)</li>
        <li>B = Baik (Lulus)</li>
        <li>C = Cukup (Kelas Khusus)</li>
        <li>D = Kurang (Tidak Lulus)</li>
    </ul>

    <div class="catatan-box">
        <div class="catatan-title">Catatan Penguji:</div>
        <p>{{ $santri->hasilTes->catatan ?? '-' }}</p>
    </div>

    <div class="signature-box">
        <div>Pandeglang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</div>
        <div>Penguji</div>
        <div class="penguji">({{ $santri->hasilTes->nama_penguji ?? '...................................' }})</div>
        <div>Nama Lengkap & Tanda Tangan</div>
    </div>
</body>
</html>
