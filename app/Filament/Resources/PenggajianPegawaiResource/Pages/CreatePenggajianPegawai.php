<?php

namespace App\Filament\Resources\PenggajianPegawaiResource\Pages;

use App\Filament\Resources\PenggajianPegawaiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Hanya fokus ke PDF dan Email
        $pdf = Pdf::loadView('pdf.invoice_gaji', ['record' => $record]);

        Mail::send('emails.penggajian', ['record' => $record], function ($message) use ($record, $pdf) {
            $message->to('karyawan@example.com') 
                ->subject('Slip Gaji - ' . $record->periode_gaji)
                ->attachData($pdf->output(), 'Slip-Gaji-' . $record->id . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}