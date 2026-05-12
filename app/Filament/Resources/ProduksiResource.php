<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduksiResource\Pages;
use App\Models\Produksi;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; //untuk dapat menggunakan action
use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
use Illuminate\Support\Facades\Storage;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationLabel = 'Produksi';

    //Tambahkan ini untuk menghilangkan s
    protected static ?string $modelLabel = 'Produksi';
    protected static ?string $pluralModelLabel = 'Produksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(3)
                    ->schema([

                        // INFORMASI PRODUK
                        Forms\Components\Section::make('Informasi Utama')
                            ->description(
                                'Produksi tanggal ' .
                                now()->locale('id')->translatedFormat('d F Y')
                            )
                            ->icon('heroicon-o-information-circle')
                            ->schema([

                                Forms\Components\TextInput::make('nama_produk')
                                    ->label('Nama Produk')
                                    ->required(),

                                Forms\Components\TextInput::make('qty_produksi')
                                    ->label('Hasil Produksi')
                                    ->numeric()
                                    ->suffix('Unit')
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_produksi')
                                    ->label('Tanggal Produksi')
                                    ->default(now())
                                    ->required(),

                            ])
                            ->columnSpan(1),

                        // PEMAKAIAN BAHAN
                        Forms\Components\Section::make('Pemakaian Bahan')
                            ->description('Input bahan baku produksi')
                            ->icon('heroicon-o-beaker')
                            ->schema([

                                Forms\Components\Repeater::make('details')
                                    ->relationship('details')
                                    ->schema([

                                        // PILIH BAHAN BAKU
                                        Forms\Components\Select::make('bahan_baku_id')
                                            ->label('Bahan Baku')
                                            ->options(
                                                BahanBaku::pluck('nama_bahan', 'id')
                                            )
                                            ->searchable()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                                $bahan = BahanBaku::find($state);

                                                if ($bahan) {

                                                    // AUTO ISI HARGA
                                                    $set('harga_satuan', $bahan->harga);

                                                    // AUTO HITUNG TOTAL
                                                    $jumlah = $get('jumlah_pakai') ?? 0;

                                                    $set('subtotal', $jumlah * $bahan->harga);
                                                }
                                            })
                                            ->required()
                                            ->columnSpan(2),

                                        // HIDDEN HARGA
                                        Forms\Components\Hidden::make('harga_satuan'),

                                        // TAMPILAN HARGA
                                        Forms\Components\Placeholder::make('harga_view')
                                            ->label('Harga')
                                            ->content(fn ($get) =>
                                                'Rp ' . number_format($get('harga_satuan') ?? 0, 0, ',', '.')
                                            ),

                                        // JUMLAH PAKAI
                                        Forms\Components\TextInput::make('jumlah_pakai')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set) {

                                                $harga = $get('harga_satuan') ?? 0;

                                                // AUTO HITUNG TOTAL
                                                $set('subtotal', $state * $harga);
                                            })
                                            ->required(),

                                        // HIDDEN SUBTOTAL
                                        Forms\Components\Hidden::make('subtotal'),

                                        // TAMPILAN TOTAL
                                        Forms\Components\Placeholder::make('subtotal_view')
                                            ->label('Total')
                                            ->content(fn ($get) =>
                                                'Rp ' . number_format($get('subtotal') ?? 0, 0, ',', '.')
                                            ),

                                    ])
                                    ->columns(5)
                                    ->defaultItems(1)
                                    ->reorderable(false)
                                    ->addActionLabel('Tambah Bahan'),

                            ])
                            ->columnSpan(2),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                            Tables\Columns\TextColumn::make('nama_produk')
                                ->label('Produk')
                                ->searchable()
                                ->sortable()
                                ->icon('heroicon-m-cake')
                                ->iconColor('danger')
                                ->description(fn ($record) =>
                                    'Tanggal Produksi: ' . Carbon::parse($record->tanggal_produksi)->format('d/m/Y')
                                ),

                            Tables\Columns\TextColumn::make('qty_produksi')
                                ->label('Qty')
                                ->badge()
                                ->color('info'),

                            Tables\Columns\TextColumn::make('details_sum_subtotal')
                                ->label('Total Biaya')
                                ->sum('details', 'subtotal')
                                ->money('IDR', locale: 'id')
                                ->badge()
                                ->color('success'),

                            Tables\Columns\TextColumn::make('created_at')
                                ->label('Ditambahkan')
                                ->since()
                                ->sinceTooltip(),

                        ])
                        ->actions([
                            Tables\Actions\ViewAction::make(),
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
                                $produksi = Produksi::all();

                                $pdf = Pdf::loadView('pdf.produksi', ['produksi' => $produksi]);

                                return response()->streamDownload(
                                    fn () => print($pdf->output()),
                                    'produksi-list.pdf'
                                );
                            })
                        ])
                        ->bulkActions([
                            Tables\Actions\BulkActionGroup::make([
                                Tables\Actions\DeleteBulkAction::make(),
                            ]),
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
            'index' => Pages\ListProduksis::route('/'),
            'create' => Pages\CreateProduksi::route('/create'),
            'edit' => Pages\EditProduksi::route('/{record}/edit'),
        ];
    }
}