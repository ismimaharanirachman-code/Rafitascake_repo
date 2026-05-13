@php
    use App\Models\PenjualanProduk;
    use Carbon\Carbon;

    $produkId    = $produk->id;
    $namaProduk  = $produk->nama_kue;
    $jenisProduk = $produk->jenis_kue ?? '-';

    $dataPenjualan = PenjualanProduk::with('penjualan')
                        ->where('produk_id', $produkId)
                        ->orderBy('created_at', 'asc')
                        ->get();

    $dataKoreksi = \App\Models\PenyesuaianStok::where('produk_id', $produkId)
                        ->orderBy('created_at', 'asc')
                        ->get();

    $semuaTransaksi = collect();

    foreach ($dataPenjualan as $j) {
        $semuaTransaksi->push([
            'tanggal'    => Carbon::parse($j->created_at),
            'keterangan' => 'Penjualan',
            'masuk'      => 0,
            'keluar'     => $j->jumlah ?? 0,
            'harga'      => $j->harga_satuan ?? $produk->harga_jual ?? 0,
        ]);
    }

    foreach ($dataKoreksi as $k) {
        $semuaTransaksi->push([
            'tanggal'    => Carbon::parse($k->created_at),
            'keterangan' => 'Koreksi Stok',
            'masuk'      => $k->jumlah,
            'keluar'     => 0,
            'harga'      => $produk->harga_jual ?? 0,
        ]);
    }

    $semuaTransaksi = $semuaTransaksi->sortBy('tanggal')->values();

    $saldoQty   = 0;
    $saldoNilai = 0;
    $hargaRata  = 0;
    $baris      = [];

    foreach ($semuaTransaksi as $t) {
        $masuk  = (int) $t['masuk'];
        $keluar = (int) $t['keluar'];
        $harga  = (float) $t['harga'];

        if ($masuk > 0) {
            $nilaiMasuk  = $masuk * $harga;
            $totalQty    = $saldoQty + $masuk;
            $totalNilai  = $saldoNilai + $nilaiMasuk;
            $hargaRata   = $totalQty > 0 ? $totalNilai / $totalQty : 0;
            $saldoQty    = $totalQty;
            $saldoNilai  = $totalNilai;

            $baris[] = [
                'tanggal'      => $t['tanggal'],
                'keterangan'   => $t['keterangan'],
                'masuk_qty'    => $masuk,
                'masuk_harga'  => $harga,
                'masuk_total'  => $nilaiMasuk,
                'keluar_qty'   => null,
                'keluar_harga' => null,
                'keluar_total' => null,
                'saldo_qty'    => $saldoQty,
                'harga_rata'   => $hargaRata,
                'saldo_nilai'  => $saldoNilai,
            ];
        } elseif ($keluar > 0) {
            $nilaiKeluar = $keluar * $hargaRata;
            $saldoQty   -= $keluar;
            $saldoNilai -= $nilaiKeluar;
            $saldoQty    = max(0, $saldoQty);
            $saldoNilai  = max(0, $saldoNilai);

            $baris[] = [
                'tanggal'      => $t['tanggal'],
                'keterangan'   => $t['keterangan'],
                'masuk_qty'    => null,
                'masuk_harga'  => null,
                'masuk_total'  => null,
                'keluar_qty'   => $keluar,
                'keluar_harga' => $hargaRata,
                'keluar_total' => $nilaiKeluar,
                'saldo_qty'    => $saldoQty,
                'harga_rata'   => $hargaRata,
                'saldo_nilai'  => $saldoNilai,
            ];
        }
    }

    $last = !empty($baris) ? end($baris) : null;
@endphp

<style>
.ks-wrap {
    font-family: sans-serif;
    font-size: 13px;
    color: #374151;
}
.ks-header {
    text-align: center;
    margin-bottom: 20px;
    line-height: 1.6;
}
.ks-header .ks-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}
.ks-header .ks-subtitle {
    font-size: 13px;
    color: #6b7280;
}
.ks-header .ks-periode {
    font-size: 12px;
    color: #9ca3af;
}
.ks-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
/* Baris header grup (Pembelian / HPP / Saldo) */
.ks-table thead tr.row-grup th {
    background-color: #f0f0f0;
    color: #374151;
    font-weight: 600;
    padding: 9px 10px;
    text-align: center;
    border: 1px solid #d1d5db;
}
.ks-table thead tr.row-grup th.col-keterangan,
.ks-table thead tr.row-grup th.col-tanggal {
    background-color: #e8e8e8;
    vertical-align: middle;
}
/* Baris sub header (Qty / Harga / Total) */
.ks-table thead tr.row-sub th {
    background-color: #f0f0f0;
    color: #6b7280;
    font-weight: 500;
    padding: 7px 10px;
    text-align: center;
    border: 1px solid #d1d5db;
    font-size: 11px;
}
/* Body */
.ks-table tbody tr {
    border-bottom: 1px solid #e5e7eb;
}
.ks-table tbody tr:nth-child(odd) {
    background-color: #fafafa;
}
.ks-table tbody tr:nth-child(even) {
    background-color: #ffffff;
}
.ks-table tbody td {
    border: 1px solid #e5e7eb;
    padding: 8px 10px;
    vertical-align: middle;
}
.ks-table tbody td.td-keterangan {
    font-weight: 500;
    color: #374151;
}
.ks-table tbody td.td-tanggal {
    text-align: center;
    white-space: nowrap;
    color: #6b7280;
}
.ks-table tbody td.td-center {
    text-align: center;
}
.ks-table tbody td.td-right {
    text-align: right;
}
.ks-table tbody td.td-saldo-nilai {
    text-align: right;
    font-weight: 600;
    color: #111827;
}
/* Saldo Akhir (tfoot) */
.ks-table tfoot tr td {
    background-color: #f3f4f6;
    border: 1px solid #d1d5db;
    padding: 9px 10px;
    font-weight: 600;
    font-size: 12px;
}
.ks-table tfoot tr td.tfoot-label {
    text-align: right;
    color: #374151;
}
.ks-table tfoot tr td.tfoot-center {
    text-align: center;
}
.ks-table tfoot tr td.tfoot-right {
    text-align: right;
}
/* Kosong */
.ks-empty {
    text-align: center;
    padding: 24px;
    color: #9ca3af;
    font-style: italic;
}
/* Catatan */
.ks-note {
    margin-top: 10px;
    font-size: 11px;
    color: #9ca3af;
    font-style: italic;
}
</style>

<div class="ks-wrap">

    {{-- Header --}}
    <div class="ks-header">
        <div class="ks-title">Laporan Kartu Stok {{ $namaProduk }}</div>
        <div class="ks-subtitle">{{ $jenisProduk }}</div>
        <div class="ks-periode">Periode {{ now()->translatedFormat('F Y') }}</div>
    </div>

    {{-- Tabel --}}
    <table class="ks-table">
        <thead>
            {{-- Baris grup --}}
            <tr class="row-grup">
                <th class="col-keterangan" rowspan="2" style="text-align:left; vertical-align:middle; min-width:110px;">
                    Keterangan
                </th>
                <th class="col-tanggal" rowspan="2" style="vertical-align:middle; min-width:90px;">
                    Tanggal
                </th>
                <th colspan="3">Pembelian</th>
                <th colspan="3">Harga Pokok Penjualan</th>
                <th colspan="3">Saldo</th>
            </tr>
            {{-- Baris sub --}}
            <tr class="row-sub">
                <th style="min-width:55px;">Qty</th>
                <th style="min-width:90px;">Harga</th>
                <th style="min-width:100px;">Total</th>

                <th style="min-width:55px;">Qty</th>
                <th style="min-width:90px;">Harga</th>
                <th style="min-width:100px;">Total</th>

                <th style="min-width:55px;">Qty</th>
                <th style="min-width:105px;">Harga Rata-rata</th>
                <th style="min-width:110px;">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($baris as $b)
            <tr>
                {{-- Keterangan --}}
                <td class="td-keterangan">{{ $b['keterangan'] }}</td>

                {{-- Tanggal --}}
                <td class="td-tanggal">{{ $b['tanggal']->translatedFormat('d M Y') }}</td>

                {{-- Pembelian --}}
                <td class="td-center">
                    {{ $b['masuk_qty'] !== null ? $b['masuk_qty'] . ' pcs' : '' }}
                </td>
                <td class="td-right">
                    {{ $b['masuk_harga'] !== null ? 'Rp ' . number_format($b['masuk_harga'], 0, ',', '.') : '' }}
                </td>
                <td class="td-right">
                    {{ $b['masuk_total'] !== null ? 'Rp ' . number_format($b['masuk_total'], 0, ',', '.') : '' }}
                </td>

                {{-- HPP / Keluar --}}
                <td class="td-center">
                    {{ $b['keluar_qty'] !== null ? $b['keluar_qty'] . ' pcs' : '' }}
                </td>
                <td class="td-right">
                    {{ $b['keluar_harga'] !== null ? 'Rp ' . number_format($b['keluar_harga'], 0, ',', '.') : '' }}
                </td>
                <td class="td-right">
                    {{ $b['keluar_total'] !== null ? 'Rp ' . number_format($b['keluar_total'], 0, ',', '.') : '' }}
                </td>

                {{-- Saldo --}}
                <td class="td-center">{{ $b['saldo_qty'] }} pcs</td>
                <td class="td-right">Rp {{ number_format($b['harga_rata'], 0, ',', '.') }}</td>
                <td class="td-saldo-nilai">Rp {{ number_format($b['saldo_nilai'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="ks-empty">
                    Belum ada riwayat pergerakan stok untuk produk ini
                </td>
            </tr>
            @endforelse
        </tbody>

        {{-- Footer / Saldo Akhir --}}
        @if($last)
        <tfoot>
            <tr>
                <td colspan="2" class="tfoot-label">Saldo Akhir</td>

                <td class="tfoot-center">
                    {{ collect($baris)->sum('masuk_qty') }} pcs
                </td>
                <td></td>
                <td class="tfoot-right">
                    Rp {{ number_format(collect($baris)->sum('masuk_total'), 0, ',', '.') }}
                </td>

                <td class="tfoot-center">
                    {{ collect($baris)->sum('keluar_qty') }} pcs
                </td>
                <td></td>
                <td class="tfoot-right">
                    Rp {{ number_format(collect($baris)->sum('keluar_total'), 0, ',', '.') }}
                </td>

                <td class="tfoot-center">{{ $last['saldo_qty'] }} pcs</td>
                <td class="tfoot-right">Rp {{ number_format($last['harga_rata'], 0, ',', '.') }}</td>
                <td class="tfoot-right">Rp {{ number_format($last['saldo_nilai'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="ks-note">
        * Kolom Pembelian akan terisi otomatis setelah fitur transaksi produksi selesai dibuat
    </div>

</div>