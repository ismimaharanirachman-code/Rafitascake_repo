<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <style>
        body{
            font-family: sans-serif;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td{
            padding: 10px;
            text-align: left;
        }

        .total{
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div style="text-align:center; margin-bottom:20px;">

    <h1 style="margin:0; color:#e11d48;">
        RAFITAS CAKE
    </h1>

    <p style="margin:5px 0;">
        Cake & Bakery
    </p>

    <p style="margin:0;">
        Jl. Sholeh Iskandar No.2, RT.02/RW.11, Kedungbadak, Tanah Sareal, Kota Bogor, Jawa Barat 16164
    </p>

    <hr style="margin-top:15px;">

</div>

    <p>
        <strong>No Nota:</strong>
        {{ $penjualan->no_nota }}
    </p>

    <p>
    <strong>Tanggal:</strong>

    {{
        \Carbon\Carbon::parse($penjualan->tanggal)->day
    }}

    {{
        [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ][\Carbon\Carbon::parse($penjualan->tanggal)->month]
    }}

    {{
        \Carbon\Carbon::parse($penjualan->tanggal)->year
    }}
</p>

    <p>
        <strong>Pelanggan:</strong>
        {{ $penjualan->pelanggan->nama_pelanggan ?? 'Pelanggan Umum' }}
    </p>

    <p>
        <strong>Metode Pembayaran:</strong>
        {{ $penjualan->metode_pembayaran == 'cash' ? 'Tunai' : 'QRIS' }}
    </p>

    <p>
        <strong>Request:</strong> 
        {{ $penjualan->request_custom }}
    </p>

    <table>

        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($penjualan->detail as $item)

                <tr>

                    <td>
                        {{ $item->produk->nama_kue ?? '-' }}
                    </td>

                    <td>
                        {{ $item->qty }}
                    </td>

                    <td>
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <div class="total">
        Total:
        Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
    </div>

</body>
</html>