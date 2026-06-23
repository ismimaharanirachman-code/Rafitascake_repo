<?php

namespace App\Filament\Resources\PenggajianPegawaiResource\Pages;

use App\Filament\Resources\PenggajianPegawaiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Jurnal;
use App\Models\JurnalDetail;

class CreatePenggajianPegawai extends CreateRecord
{
    protected static string $resource = PenggajianPegawaiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // ======================
        // BUAT JURNAL
        // ======================
        $jurnal = Jurnal::create([
            'tanggal' => $record->tanggal_gaji,
            'keterangan' => 'Penggajian Periode ' . $record->periode_gaji,
        ]);

        // Debit Beban Gaji
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => 'Beban Gaji',
            'debit' => $record->total_gaji,
            'kredit' => 0,
        ]);

        // Kredit Kas / Utang Gaji
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => $record->status_pembayaran == 'Lunas'
                        ? 'Kas'
                        : 'Utang Gaji',
            'debit' => 0,
            'kredit' => $record->total_gaji,
        ]);

        // ======================
        // PDF SLIP GAJI
        // ======================
        $pdf = Pdf::loadView('pdf.invoice_gaji', [
            'record' => $record
        ]);

        Mail::send(
            'emails.penggajian',
            ['record' => $record],
            function ($message) use ($record, $pdf) {
                $message->to('karyawan@example.com')
                    ->subject('Slip Gaji - ' . $record->periode_gaji)
                    ->attachData(
                        $pdf->output(),
                        'Slip-Gaji-' . $record->id_penggajian . '.pdf',
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
            }
        );
    }
}