<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianPegawaiResource\Pages;
use App\Models\PenggajianPegawai;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; //untuk dapat menggunakan action
use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
use Illuminate\Support\Facades\Storage;

class PenggajianPegawaiResource extends Resource
{
    protected static ?string $model = PenggajianPegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Penggajian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penggajian')
                    ->schema([
                        Forms\Components\TextInput::make('id_penggajian')
                            ->label('ID Penggajian')
                            ->default(fn () => 'GJI' . str_pad(PenggajianPegawai::count() + 1, 3, '0', STR_PAD_LEFT))
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('id_pegawai')
                            ->label('Karyawan')
                            ->relationship('pegawai', 'nama_pegawai')
                            ->getOptionLabelFromRecordUsing(fn (Pegawai $record) => "{$record->id_pegawai} - {$record->nama_pegawai}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $pegawai = Pegawai::find($state);
                                if ($pegawai) {
                                    $gaji = number_format($pegawai->gaji, 0, ',', '.');
                                    $set('gaji_pokok', $gaji);
                                    $set('total_gaji', $gaji);
                                }
                            }),

                        Forms\Components\DatePicker::make('tanggal_gaji')
                            ->label('Tanggal Gaji')
                            ->default(now())
                            ->required(),

                        Forms\Components\TextInput::make('periode_gaji')
                            ->label('Periode Gaji')
                            ->placeholder('Contoh: Juli 2026')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Gaji')
                    ->schema([
                        Forms\Components\TextInput::make('gaji_pokok')
                            ->label('Gaji Pokok')
                            ->prefix('Rp')
                            ->required()
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                            ->dehydrateStateUsing(fn ($state) => str_replace('.', '', $state))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                        Forms\Components\TextInput::make('tunjangan')
                            ->label('Tunjangan')
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                            ->dehydrateStateUsing(fn ($state) => str_replace('.', '', $state))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                        Forms\Components\TextInput::make('potongan')
                            ->label('Potongan')
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                            ->dehydrateStateUsing(fn ($state) => str_replace('.', '', $state))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                        Forms\Components\TextInput::make('total_gaji')
                            ->label('Total Gaji Diterima')
                            ->prefix('Rp')
                            ->readonly()
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                            ->dehydrateStateUsing(fn ($state) => str_replace('.', '', $state))
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options(['Transfer' => 'Transfer', 'Cash' => 'Cash'])
                            ->required(),

                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options(['Pending' => 'Pending', 'Lunas' => 'Lunas'])
                            ->required(),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function hitungTotal(callable $set, callable $get): void
    {
        $gaji = (int) str_replace('.', '', $get('gaji_pokok') ?? 0);
        $tunjangan = (int) str_replace('.', '', $get('tunjangan') ?? 0);
        $potongan = (int) str_replace('.', '', $get('potongan') ?? 0);

        $total = $gaji + $tunjangan - $potongan;

        $set('total_gaji', number_format($total, 0, ',', '.'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_penggajian')->label('ID')->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama_pegawai')->label('Karyawan')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_gaji')->label('Tanggal')->date(),
                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('status_pembayaran')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
                    $PenggajianPegawai = PenggajianPegawai::all();

                    $pdf = Pdf::loadView('pdf.PenggajianPegawai', ['PenggajianPegawai' => $PenggajianPegawai]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'PenggajianPegawai-list.pdf'
                    );
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajianPegawais::route('/'),
            'create' => Pages\CreatePenggajianPegawai::route('/create'),
            'edit' => Pages\EditPenggajianPegawai::route('/{record}/edit'),
        ];
    }
}