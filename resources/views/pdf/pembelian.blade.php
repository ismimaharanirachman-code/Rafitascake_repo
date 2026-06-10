<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Bahan Baku - Rafita's Cake and Bakery</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #444; 
            background-color: #fff;
            line-height: 1.6;
        }

        .header-container {
            text-align: center; 
            border-bottom: 3px solid #db2777; 
            margin-bottom: 30px;
            padding-bottom: 15px;
            position: relative;
        }

        .header-container h2 { 
            margin: 0;
            font-size: 24px;
            color: #be185d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-container p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #f472b6; 
            font-weight: bold;
        }

        .doc-info {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px;
        }
        
        th { 
            background-color: #be185d; 
            color: #ffffff; 
            padding: 12px 10px;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #be185d;
        }
        
        td { 
            padding: 10px; 
            text-align: center;
            font-size: 11px;
            border: 1px solid #fbcfe8;
        }

        tr:nth-child(even) {
            background-color: #fff1f2;
        }

        .total-row {
            background-color: #fce7f3 !important;
            font-weight: bold;
            color: #be185d;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            font-size: 12px;
        }

        .signature-space {
            height: 80px;
        }

        .footer-note {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #f472b6;
            border-top: 1px solid #fbcfe8;
            padding-top: 5px;
        }

        /* Warna Status */
        .status-badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h2>Rafita's Cake and Bakery</h2>
        <p>Laporan Pembelian Bahan Baku</p>
    </div>

    <table class="doc-info">
        <tr>
            <td style="text-align: left; border: none; padding: 0;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Metode</th>
                <th>Status</th> 
                <th>Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($pembelian as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ $item->kode_pembelian }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                <td style="text-align: left;">{{ $item->supplier->Nama_Supplier ?? '-' }}</td>
                <td>{{ $item->payment_method }}</td>
                <td class="status-badge" style="color: {{ $item->status_pembayaran == 'Lunas' ? '#16a34a' : '#dc2626' }}">
                    {{ $item->status_pembayaran }}
                </td>
                <td style="text-align: right;">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $item->total; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right; border: 1px solid #fbcfe8;">TOTAL KESELURUHAN</td>
                <td style="text-align: right; border: 1px solid #fbcfe8;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Bogor, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Disetujui oleh,</p>
            <div class="signature-space"></div>
            <p><strong>____________________</strong></p>
            <p>Manager Operasional</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>