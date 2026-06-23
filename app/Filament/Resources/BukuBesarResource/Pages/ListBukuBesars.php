<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;

class ListBukuBesars extends ListRecords
{
    protected static string $resource = BukuBesarResource::class;

    // 1. Membuat Form Filter Akun, Periode Awal, dan Periode Akhir
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('coa_id')
                    ->label('Pilih Akun (COA)')
                    ->options(\App\Models\Coa::all()->pluck('nama_akun', 'id'))
                    ->required()
                    ->searchable(),
                DatePicker::make('periode_awal')
                    ->label('Periode Awal')
                    ->default(now()->startOfMonth()),
                DatePicker::make('periode_akhir')
                    ->label('Periode Akhir')
                    ->default(now()->endOfMonth()),
            ])
            ->columns(3);
    }

    // 2. Query Otomatis Menggabungkan Tabel Jurnal Sesuai Panduan Dosen
    public function getFilteredRecords()
    {
        $state = $this->form->getState();

        // Jika user belum memilih akun COA, tampilkan data kosong terlebih dahulu
        if (empty($state['coa_id'])) {
            return collect([]);
        }

        // Query join antara jurnal_detail, jurnal, dan coa sesuai instruksi modul
        $query = DB::table('jurnal_detail')
            ->join('jurnal', 'jurnal_detail.jurnal_id', '=', 'jurnal.id')
            ->join('coa', 'jurnal_detail.coa_id', '=', 'coa.id')
            ->select(
                'jurnal.id as jurnal_id',
                'jurnal.tgl as tanggal',
                'jurnal.no_referensi',
                'jurnal_detail.deskripsi as keterangan',
                'coa.nama_akun',
                'jurnal_detail.debit',
                'jurnal_detail.credit as kredit'
            )
            ->where('jurnal_detail.coa_id', $state['coa_id'])
            ->orderBy('jurnal.tgl', 'asc')
            ->orderBy('jurnal.id', 'asc');

        // Filter rentang tanggal berdasarkan input user
        if (!empty($state['periode_awal'])) {
            $query->where('jurnal.tgl', '>=', $state['periode_awal']);
        }

        if (!empty($state['periode_akhir'])) {
            $query->where('jurnal.tgl', '<=', $state['periode_akhir']);
        }

        return $query->get();
    }
}