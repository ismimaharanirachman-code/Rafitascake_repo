<!DOCTYPE html>
<html>
<head>
    <title>Daftar Penjualan</title>

    <style>

        body{
            font-family: sans-serif;
        }

        h2{
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid black;
        }

        th, td{
            padding:10px;
            text-align:left;
        }

    </style>
</head>

<body>

    <h2>Daftar Penjualan Rafitas Cake</h2>

    <table>

        <thead>
            <tr>
                <th>No Nota</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($penjualan as $item)

                <tr>

                    <td>{{ $item->no_nota }}</td>

                    <td>{{ $item->tanggal }}</td>

                    <td>
                        {{ $item->pelanggan->nama_pelanggan ?? 'Umum' }}
                    </td>

                    <td>{{ $item->metode_pembayaran }}</td>

                    <td>{{ $item->status }}</td>

                    <td>
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>