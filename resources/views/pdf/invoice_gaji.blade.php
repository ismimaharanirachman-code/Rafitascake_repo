<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji - {{ $record->pegawai->nama_pegawai ?? 'Pegawai' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #e11d48;
            font-size: 28px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .slip-title {
            text-align: center;
            text-decoration: underline;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .info-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .main-table th {
            background-color: #f8f9fa;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .main-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .total-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff1f2;
            border: 1px solid #fda4af;
            border-radius: 5px;
        }
        .total-row {
            display: table;
            width: 100%;
        }
        .total-label {
            display: table-cell;
            font-weight: bold;
            font-size: 16px;
        }
        .total-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            color: #e11d48;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            font-size: 13px;
        }
        .footer-note {
            margin-top: 100px;
            font-size: 10px;
            font-style: italic;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
@php
    $pegawai = $record->pegawai;
    $jabatan = $pegawai->jabatan ?? null;
@endphp

<div class="container">

    <div class="header">
        <h1>RAFITAS CAKE</h1>
        <p>Jl. Sholeh Iskandar No.2, RT.02/RW.11, Kedungbadak, Bogor</p>
        <p>Email: info@rafitascake.com | Telp: (0251) 1234567</p>
    </div>

    <div class="slip-title">Slip Gaji Pegawai</div>

    <table class="info-table">
        <tr>
            <td>ID Slip</td>
            <td>: <strong>{{ $record->id_penggajian }}</strong></td>
            <td>Periode</td>
            <td>: {{ $record->periode_gaji }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: {{ $pegawai->nama_pegawai ?? '-' }}</td>
            <td>Tgl Bayar</td>
            <td>: {{ \Carbon\Carbon::parse($record->tanggal_gaji)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $jabatan->nama_jabatan ?? '-' }}</td>
            <td>Metode</td>
            <td>: {{ $record->metode_pembayaran }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th style="text-align:right;">NOMINAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td style="text-align:right;">
                    Rp {{ number_format($jabatan->gaji_pokok ?? 0, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Tunjangan</td>
                <td style="text-align:right;">
                    Rp {{ number_format($record->tunjangan ?? 0, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td style="color:#e11d48;">Potongan</td>
                <td style="text-align:right;color:#e11d48;">
                    - Rp {{ number_format($record->potongan ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">TOTAL GAJI BERSIH</div>
            <div class="total-value">
                Rp {{ number_format($record->total_gaji ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Bogor, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Bendahara Operasional</p>
            <br><br><br>
            <p>(____________________)</p>
            <p>Noor Rafita</p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="footer-note">
        *Dokumen ini dihasilkan otomatis oleh sistem dan sah tanpa tanda tangan basah.
    </div>

</div>
</body>
</html>