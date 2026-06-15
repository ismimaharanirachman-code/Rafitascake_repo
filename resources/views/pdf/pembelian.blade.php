<!DOCTYPE html>
<html>
<head>
    <title>Pembelian Bahan Baku</title>
    <style>
        @page { margin: 100px 25px; }
        body {
            font-family: 'Nunito', 'Helvetica', sans-serif;
            color: #4a4a4a;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #f472b6; /* Pink Lucu (Pink 400) */
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        th {
            background-color: #fce7f3; /* Pink sangat muda (Pink 100) */
            color: #be185d; /* Teks Maroon Pink */
            padding: 15px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            border-bottom: 2px solid #fbcfe8;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #fdf2f8;
            font-size: 12px;
            color: #666;
        }
        /* Baris selang-seling yang halus */
        tbody tr:nth-child(even) {
            background-color: #fffafb;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #f9a8d4;
        }
    </style>
</head>
<body>
    <h2>Pembelian Bahan Baku</h2>
    
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal Pembelian</th>
                <th>Supplier</th>
                <th class="text-right">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $item)
            <tr>
                <tbody>
    @foreach($pembelian as $item)
    <tr>
        <td style="font-weight: bold; color: #db2777;">{{ $item->kode_pembelian }}</td>
        <td>{{ $item->created_at->format('d M Y') }}</td>
        
        
        <td>{{ $item->supplier->nama_supplier ?? $item->supplier->Nama_Supplier ?? '-' }}</td>
        
        <td class="text-right" style="color: #be185d; font-weight: bold;">
            
            Rp {{ number_format($item->total ?? $item->total_harga ?? 0, 0, ',', '.') }}
        </td>
    </tr>
    @endforeach
</tbody>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>