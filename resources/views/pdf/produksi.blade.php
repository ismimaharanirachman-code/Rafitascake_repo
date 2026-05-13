<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Produksi Bakery</title>

    <style>
        /* /* // STYLING DASAR: Menggunakan font sans-serif agar seragam & bersih */ */
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #4b5563;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }

        /* /* // HEADER: Mengatur judul agar di tengah dengan warna pink khas */ */
        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #be185d;
            font-weight: bold;
        }

        /* /* // INFO BOX: Menampilkan tanggal cetak real-time */ */
        .info-box {
            text-align: center;
            background: #fdf2f8;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 25px;
            color: #be185d;
            border: 1px solid #fce7f3;
        }

        /* /* // TABLE: Struktur tabel tanpa kolom status */ */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #fce7f3;
        }

        th {
            padding: 12px;
            color: #be185d;
            border: 1px solid #fbcfe8;
            text-align: center;
        }

        td {
            padding: 12px;
            border: 1px solid #f3f4f6;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #fffafc;
        }

        .qty-badge {
            background: #fdf2f8;
            color: #be185d;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: bold;
        }

        /* /* // TOTAL CONTAINER: Pembungkus agar box pink nempel ke kanan */ */
        .total-container {
            text-align: right; 
            margin-top: 20px;
        }

        /* /* // TOTAL BOX: Lebar otomatis (inline-block) agar tidak kepanjangan */ */
        .total-box {
            display: inline-block; 
            background: #be185d;
            padding: 15px 25px; 
            border-radius: 10px;
            color: white;
            text-align: right;
        }

        /* /* // FOOTER: Informasi hak cipta di paling bawah */ */
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Rafita's Cake & Bakery</h1>
        <p>Laporan Produksi Bakery</p>
    </div>

    <div class="info-box">
        <strong>TANGGAL CETAK:</strong> 
        {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y • H:i:s') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Produk</th>
                <th>Quantity</th>
                <th>Total Biaya</th>
                <th>Tanggal Produksi</th>
            </tr>
        </thead>

        <tbody>
            @php $grandTotal = 0; @endphp

            @foreach($produksi as $index => $p)
                @php
                    $totalBiaya = $p->details->sum('subtotal');
                    $grandTotal += $totalBiaya;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $p->nama_produk }}</td>
                    <td>
                        <span class="qty-badge">{{ $p->qty_produksi }} Units</span>
                    </td>
                    <td style="color: #be185d; font-weight: bold;">
                        Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_produksi)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-container">
        <div class="total-box">
            <span style="font-size: 11px; opacity: 0.9; display: block; margin-bottom: 5px;">GRAND TOTAL PRODUKSI</span>
            <span style="font-size: 20px; font-weight: bold;">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="footer">
        Rafita's Cake and Bakery • Sweet Quality, Sweet Report
    </div>

</body>
</html>