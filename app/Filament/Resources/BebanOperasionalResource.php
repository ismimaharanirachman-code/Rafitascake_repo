<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BebanOperasionalResource\Pages;
use App\Filament\Resources\BebanOperasionalResource\RelationManagers;
use App\Models\BebanOperasional;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
//tambah tombol pdf 
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BebanOperasionalResource extends Resource
{
    protected static ?string $model = BebanOperasional::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            DatePicker::make('tanggal')
                ->required(),

            Select::make('coa_id')
                ->options(
                    Coa::where('tipe_akun', 'Beban')
                        ->pluck('nama_akun', 'kode_akun')
                        )
                ->searchable()
                ->required(),

            TextInput::make('keterangan'),

            TextInput::make('nominal')
                ->numeric()
                ->required(),
            FileUpload::make('lampiran')
                ->label('Bukti Transaksi')
                ->directory('bukti-transaksi')
                ->disk('public')
                ->acceptedFileTypes([
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
    ])
    ->downloadable()
    ->openable(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('tanggal')
                ->date('d F Y'),

            Tables\Columns\TextColumn::make('coa_id')
                ->label('Kode Akun'),

            Tables\Columns\TextColumn::make('keterangan'),

            Tables\Columns\TextColumn::make('nominal')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Tables\Columns\TextColumn::make('lampiran')
                ->label('Bukti')
                ->formatStateUsing(fn () => 'Lihat File')
                ->url(fn ($record) => asset('storage/' . $record->lampiran))
                ->openUrlInNewTab(),        
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        // tombol tambahan
            ->headerActions([
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $BebanOperasional = BebanOperasional::all();

                    $pdf = Pdf::loadView('pdf.BebanOperasional', ['BebanOperasional' => $BebanOperasional]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'BebanOperasional-list.pdf'
                    );
                })
            ])

        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
            ->label('Hapus')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation(),
        ]);
    
}       

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBebanOperasionals::route('/'),
            'create' => Pages\CreateBebanOperasional::route('/create'),
            'edit' => Pages\EditBebanOperasional::route('/{record}/edit'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    dd($data);
}

}
