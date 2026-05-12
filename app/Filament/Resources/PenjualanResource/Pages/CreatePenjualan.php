<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Produk;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Midtrans\Config;
use Midtrans\Snap;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    // 🔥 FIX TOTAL + STATUS + PELANGGAN
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // pelanggan umum otomatis
        if (($data['pelanggan_mode'] ?? null) === 'umum') {
            $data['pelanggan_id'] = 1;
        }

        // 🔥 HITUNG TOTAL DARI DETAIL
        $total = collect($data['detail'] ?? $this->data['detail'] ?? [])
            ->sum(function ($item) {

                $harga = (int) ($item['harga'] ?? 0);
                $qty = (int) ($item['qty'] ?? 0);

                return $harga * $qty;
            });

        $data['total_harga'] = $total;

        // 🔥 AUTO STATUS
        if (($data['pelanggan_mode'] ?? null) === 'custom') {
            $data['status'] = 'diproses';
        } else {
            $data['status'] = 'selesai';
        }

        return $data;
    }

    // 🔥 VALIDASI STOK
    protected function beforeCreate(): void
    {
        $detail = $this->data['detail'] ?? [];

        foreach ($detail as $item) {

            $produk = Produk::find($item['produk_id'] ?? null);

            if (!$produk) {
                continue;
            }

            if ((int) $item['qty'] > (int) $produk->stok) {

                Notification::make()
                    ->title('Stok tidak cukup')
                    ->body("{$produk->nama_kue} hanya tersisa {$produk->stok}")
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }

    // 🔥 KURANGI STOK + MIDTRANS
    protected function afterCreate(): void
    {
        $penjualan = $this->record;

        foreach ($penjualan->detail as $item) {

            $produk = Produk::find($item->produk_id);

            if ($produk) {
                $produk->decrement('stok', (int) $item->qty);
            }
        }

        // 🔥 QRIS MIDTRANS
        if ($penjualan->metode_pembayaran == 'qris') {

            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $penjualan->id,
                    'gross_amount' => $penjualan->total_harga,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            session()->put('snapToken', $snapToken);
        }
    }

    protected function getRedirectUrl(): string
    {
        if ($this->record->metode_pembayaran == 'qris') {
            return '/midtrans-payment';
        }

        return $this->getResource()::getUrl('index');
    }
}