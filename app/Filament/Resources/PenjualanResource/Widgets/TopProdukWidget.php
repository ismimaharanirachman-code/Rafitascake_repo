<?php

namespace App\Filament\Resources\PenjualanResource\Widgets;

use App\Models\Produk;
use Filament\Widgets\Widget;

class TopProdukWidget extends Widget
{
    protected static string $view = 'filament.resources.penjualan-resource.widgets.top-produk-widget';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $produk = Produk::query()
    ->join('penjualan_produk', 'produk.id', '=', 'penjualan_produk.produk_id')
    ->selectRaw('
        produk.id,
        produk.nama_kue,
        produk.gambar,
        SUM(penjualan_produk.qty) as total_terjual
    ')
    ->groupBy(
        'produk.id',
        'produk.nama_kue',
        'produk.gambar'
    )
    ->orderByDesc('total_terjual')
    ->limit(3)
    ->get();;

        return [
            'produk' => $produk,
        ];
    }
}