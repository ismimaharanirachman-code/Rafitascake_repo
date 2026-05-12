<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Penggajian Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">LAPORAN PENGGAJIAN PEGAWAI</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Tgl Gaji</th>
                <th>Periode</th>
                <th>Gaji Pokok</th>
                <th>Tunjangan</th>
                <th>Potongan</th>
                <th>Total Gaji</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Pastikan variabel dari Controller namanya $penggajian_pegawai --}}
            @foreach($PenggajianPegawai as $key => $p)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $p->pegawai->nama_pegawai ?? 'N/A' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_gaji)->format('d/m/Y') }}</td>
                <td>{{ $p->periode_gaji }}</td>
                <td class="text-right">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($p->tunjangan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($p->potongan, 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($p->total_gaji, 0, ',', '.') }}</td>
                <td>{{ $p->metode_pembayaran }}</td>
                <td>{{ $p->status_pembayaran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>