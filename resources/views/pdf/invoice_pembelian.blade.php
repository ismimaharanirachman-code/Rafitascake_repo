<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #444; 
            background-color: #fff;
            padding: 40px;
        }
    
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 { 
            color: #be185d; 
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .shop-name {
            color: #f472b6; 
            font-size: 16px;
            margin-top: 5px;
            font-weight: bold;
        }
        .line {
            border-bottom: 3px solid #db2777; 
            margin: 15px 0 20px 0;
        }
        .info-table { 
            width: 100%; 
            font-size: 13px; 
            margin-bottom: 20px;
            color: #555;
        }
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .items-table th { 
            background-color: #be185d; 
            color: #ffffff; 
            padding: 12px 10px;
            border: 1px solid #be185d;
            font-size: 12px;
            text-transform: uppercase;
        }
        .items-table td { 
            padding: 10px; 
            border: 1px solid #fbcfe8; 
            text-align: center;
            font-size: 12px;
        }
    
        .total-section {
            text-align: right;
            margin-top: 25px;
            font-size: 18px;
            font-weight: bold;
            color: #be185d; 
            padding-top: 10px;
            border-top: 2px double #fbcfe8;
        }
        .footer {
            text-align: center;
            color: #f472b6;
            font-size: 10px;
            margin-top: 30px;
            font-style: italic;
        }
        .page-break { page-break-after: always; }
        
        .status-text {
            font-weight: bold;
            color: #be185d;
        }
    </style>
</head>
<body>
    @php
        if (isset($record) && !isset($records)) {
            $records = collect([$record]);
        }
    @endphp

    @foreach($records as $record)
        <div class="container">
            <div class="header">
                <h1>Rafita's Cake and Bakery</h1>
                <div class="shop-name">INVOICE PEMBELIAN BAHAN BAKU</div>
                <div class="line"></div>
            </div>

            <table class="info-table">
                <tr>
                    <td width="50%"><strong>Kode Transaksi:</strong> <span style="color: #be185d;">{{ $record->kode_pembelian }}</span></td>
                    <td width="50%" style="text-align: right;">
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->tanggal)->locale('id')->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Supplier:</strong> {{ $record->supplier->Nama_Supplier ?? '-' }}</td>
                    <td style="text-align: right;">
                        <strong>Status:</strong> 
                        <span class="status-text" style="color: {{ $record->status_pembayaran == 'Lunas' ? '#16a34a' : '#dc2626' }}">
                            {{ $record->status_pembayaran }}
                        </span>
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->detailPembelian as $detail)
                    <tr>
                        <td style="text-align: left; font-weight: bold;">{{ $detail->bahanBaku->nama_bahan ?? 'Bahan' }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                TOTAL PEMBAYARAN: Rp {{ number_format($record->total, 0, ',', '.') }}
            </div>

        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>