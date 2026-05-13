<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Beban Operasional</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center;">
        LAPORAN BEBAN OPERASIONAL
    </h2>

    <hr>

    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Akun</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>

        <tbody>

            @foreach($BebanOperasional as $key => $b)

            <tr>

                <td class="text-center">
                    {{ $key + 1 }}
                </td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($b->tanggal)->format('d/m/Y') }}
                </td>

                <td>
                    {{ $b->coa_id }}
                </td>

                <td>
                    {{ $b->keterangan }}
                </td>

                <td class="text-right font-bold">
                    Rp {{ number_format($b->nominal, 0, ',', '.') }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>