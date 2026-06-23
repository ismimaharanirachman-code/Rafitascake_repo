<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use App\Filament\Resources\PembelianBahanBakuResource;
use Filament\Resources\Pages\CreateRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Jurnal;
use App\Models\JurnalDetail;

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
        
        // ==========================
// JURNAL OTOMATIS PEMBELIAN
// ==========================

$jurnal = Jurnal::create([
    'tanggal' => $record->tanggal,
    'keterangan' => 'Pembelian Bahan Baku',
]);

$total = $record->detailPembelian()->sum('subtotal');
// Debit Persediaan Bahan Baku
JurnalDetail::create([
    'jurnal_id' => $jurnal->id,
    'akun' => 'Persediaan Bahan Baku',
    'debit' => $total,
    'kredit' => 0,
]);

// Kredit Kas
JurnalDetail::create([
    'jurnal_id' => $jurnal->id,
    'akun' => 'Kas',
    'debit' => 0,
    'kredit' => $total,
]);
    

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