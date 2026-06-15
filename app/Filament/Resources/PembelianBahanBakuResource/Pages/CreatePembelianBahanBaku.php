<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use App\Filament\Resources\PembelianBahanBakuResource;
use Filament\Resources\Pages\CreateRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class CreatePembelianBahanBaku extends CreateRecord
{
    protected static string $resource = PembelianBahanBakuResource::class;
    protected static bool $stockProcessed = false;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        foreach ($data['detailPembelian'] ?? [] as &$detail) {
            $detail['harga'] = (int) str_replace(['Rp', '.', ' '], '', $detail['harga'] ?? 0);
            $detail['subtotal'] = (int) str_replace(['Rp', '.', ' '], '', $detail['subtotal'] ?? 0);
            $total += $detail['subtotal'];
        }
        $data['total'] = $total;
        return $data;
    }
    protected function afterCreate(): void
    {
        $record = $this->record;
        $details = $record->detailPembelian; 
    

        $pdf = Pdf::loadView('pdf.invoice_pembelian', [
            'record' => $record,
            'details' => $details 
        ]);

        Mail::send('emails.pembelian', ['data' => $record], function ($message) use ($record, $pdf) {
            $message->to('pembelian@example.com')
                ->subject('Invoice Pembelian Bahan Baku - ' . $record->tanggal)
                ->attachData($pdf->output(), 'Invoice-' . $record->kode_pembelian . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}