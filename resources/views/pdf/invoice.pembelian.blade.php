<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Pembelian - {{ $record->kode_pembelian }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #be185d; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #fce7f3; border: 1px solid #fbcfe8; padding: 8px; text-align: left; }
        .items-table td { border: 1px solid #eee; padding: 8px; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; font-weight: bold; font-size: 14px; color: #be185d; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="title">INVOICE PEMBELIAN</div>
        
        <table class="info-table">
            <tr>
                <td>
                    <strong>Kepada:</strong><br>
                    {{ $record->Nama_Supplier }}<br>
                    {{ $record->email_supplier }}
                </td>
                <td class="text-right">
                    <strong>No. Faktur:</strong> {{ $record->kode_pembelian }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}<br>
                    <strong>Status:</strong> Lunas
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Bahan Baku</th>
                    <th>Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $item)
                <tr>
                    <td>{{ $item->nama_bahan_baku }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section text-right">
            Total Pembayaran: Rp {{ number_format($record->total, 0, ',', '.') }}
        </div>

        <div style="margin-top: 50px;">
            <p>Catatan: Pembayaran ini merupakan bukti sah pemesanan bahan baku untuk operasional Rafita's Cake.</p>
        </div>
    </div>
</body>
</html>