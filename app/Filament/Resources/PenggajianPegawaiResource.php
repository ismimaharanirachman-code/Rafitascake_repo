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
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; // Tambahan untuk Mailtrap

class PenggajianPegawaiResource extends Resource
{
    protected static ?string $model = PenggajianPegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Penggajian';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->description('Pilih pegawai dan tentukan periode penggajian.')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                Forms\Components\TextInput::make('id_penggajian')
                                    ->label('ID Penggajian')
                                    ->default(fn () => 'GJI' . str_pad(PenggajianPegawai::count() + 1, 3, '0', STR_PAD_LEFT))
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefix('INV'),

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
                                            $gaji = $pegawai->gaji;
                                            $set('gaji_pokok', number_format($gaji, 0, ',', '.'));
                                            $set('total_gaji', number_format($gaji, 0, ',', '.'));
                                        }
                                    }),

                                Forms\Components\DatePicker::make('tanggal_gaji')
                                    ->label('Tanggal Pembayaran')
                                    ->default(now())
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y'),

                                Forms\Components\TextInput::make('periode_gaji')
                                    ->label('Periode Gaji')
                                    ->placeholder('Contoh: Juli 2026')
                                    ->required(),
                            ])->columns(2),

                        Forms\Components\Section::make('Rincian Nominal')
                            ->description('Input tunjangan dan potongan untuk menghitung total.')
                            ->icon('heroicon-m-calculator')
                            ->schema([
                                Forms\Components\TextInput::make('gaji_pokok')
                                    ->label('Gaji Pokok')
                                    ->prefix('Rp')
                                    ->required()
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->dehydrateStateUsing(fn ($state) => $state ? str_replace('.', '', $state) : 0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                                Forms\Components\TextInput::make('tunjangan')
                                    ->label('Tunjangan')
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->dehydrateStateUsing(fn ($state) => $state ? str_replace('.', '', $state) : 0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                                Forms\Components\TextInput::make('potongan')
                                    ->label('Potongan')
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->dehydrateStateUsing(fn ($state) => $state ? str_replace('.', '', $state) : 0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $get) => self::hitungTotal($set, $get)),

                                Forms\Components\TextInput::make('total_gaji')
                                    ->label('Total Gaji Diterima')
                                    ->prefix('Rp')
                                    ->readonly()
                                    ->extraInputAttributes(['class' => 'font-bold text-lg text-primary-600'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->dehydrateStateUsing(fn ($state) => str_replace('.', '', $state))
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Metode & Status')
                            ->schema([
                                Forms\Components\Select::make('metode_pembayaran')
                                    ->label('Metode')
                                    ->options([
                                        'Transfer' => 'Transfer Bank',
                                        'Cash' => 'Tunai/Cash'
                                    ])
                                    ->native(false)
                                    ->required(),

                                Forms\Components\Select::make('status_pembayaran')
                                    ->label('Status')
                                    ->options([
                                        'Pending' => 'Pending',
                                        'Lunas' => 'Lunas'
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),

                        Forms\Components\Section::make('Catatan')
                            ->schema([
                                Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan Tambahan')
                                    ->rows(3),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
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
                Tables\Columns\TextColumn::make('id_penggajian')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama_pegawai')
                    ->label('Karyawan')
                    ->description(fn (PenggajianPegawai $record): string => $record->periode_gaji ?? '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_gaji')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->money('IDR', locale: 'id')
                    ->alignment('right')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Pending' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Lunas' => 'heroicon-m-check-circle',
                        'Pending' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options([
                        'Pending' => 'Pending',
                        'Lunas' => 'Lunas',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat')->color('info'),
                Tables\Actions\EditAction::make(),
                
                Action::make('downloadInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function (PenggajianPegawai $record) {
                        $pdf = Pdf::loadView('pdf.invoice_gaji', ['record' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Invoice-' . ($record->id_penggajian ?? $record->id) . '.pdf'
                        );
                    }),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh Laporan PDF')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->action(function () {
                        $data = PenggajianPegawai::all();
                        $pdf = Pdf::loadView('pdf.PenggajianPegawai', ['PenggajianPegawai' => $data]);
                        $pdf->setpaper('a4','potrait');
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Laporan-Seluruh-Penggajian.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
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