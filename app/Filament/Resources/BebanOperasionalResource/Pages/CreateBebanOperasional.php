<?php

namespace App\Filament\Resources\BebanOperasionalResource\Pages;

use App\Filament\Resources\BebanOperasionalResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Jurnal;
use App\Models\JurnalDetail;

class CreateBebanOperasional extends CreateRecord
{
    protected static string $resource = BebanOperasionalResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Membuat header jurnal
        $jurnal = Jurnal::create([
            'tanggal' => $record->tanggal,
            'keterangan' => 'Beban Operasional - ' . $record->keterangan,
        ]);

        // Detail jurnal (Debit)
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => 'Beban Operasional',
            'debit' => $record->nominal,
            'kredit' => 0,
        ]);

        // Detail jurnal (Kredit)
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => 'Kas',
            'debit' => 0,
            'kredit' => $record->nominal,
        ]);
    }
}