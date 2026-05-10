<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            // 🧾 DATA PENJUALAN
            Forms\Components\Section::make('Data Penjualan')
                ->schema([

                    Forms\Components\Grid::make(2)
                        ->schema([

                            Forms\Components\Select::make('pelanggan_mode')
                                ->label('Jenis Pelanggan')
                                ->options([
                                    'umum' => 'Pelanggan Umum',
                                    'custom' => 'Pelanggan Custom',
                                ])
                                ->default('umum')
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set) {

                                    // AUTO STATUS
                                    if ($state === 'umum') {
                                        $set('status', 'selesai');

                                        $umum = \App\Models\Pelanggan::find(1);

                                        if ($umum) {
                                            $set('pelanggan_id', $umum->id_pelanggan);
                                            $set('nomor_hp_display', $umum->nomor_hp);
                                            $set('alamat_display', $umum->alamat);
                                        } else {
                                            $set('pelanggan_id', null);
                                        }
                                    }

                                    if ($state === 'custom') {

                                        $set('status', 'diproses');

                                        $set('pelanggan_id', null);
                                        $set('nomor_hp_display', null);
                                        $set('alamat_display', null);
                                    }
                                }),
                            Forms\Components\Select::make('pelanggan_id')
                                ->label('Pelanggan')
                                ->relationship('pelanggan', 'nama_pelanggan')
                                ->searchable()
                                ->preload()
                                ->visible(fn ($get) => $get('pelanggan_mode') === 'custom')
                                ->required(fn ($get) => $get('pelanggan_mode') === 'custom')

                                ->createOptionForm([

                                    Forms\Components\TextInput::make('nama_pelanggan')
                                        ->required(),

                                    Forms\Components\TextInput::make('nomor_hp')
                                        ->tel()
                                        ->required(),

                                    Forms\Components\Textarea::make('alamat')
                                        ->required(),

                                ])

                                ->createOptionUsing(function (array $data) {

                                    $pelanggan = \App\Models\Pelanggan::create([
                                        'nama_pelanggan' => $data['nama_pelanggan'],
                                        'nomor_hp' => $data['nomor_hp'],
                                        'alamat' => $data['alamat'],
                                    ]);

                                    return $pelanggan->id_pelanggan;
                                })

                                ->reactive()

                                ->afterStateUpdated(function ($state, callable $set) {

                                    $pelanggan = \App\Models\Pelanggan::find($state);

                                    if ($pelanggan) {
                                        $set('nomor_hp_display', $pelanggan->nomor_hp);
                                        $set('alamat_display', $pelanggan->alamat);
                                    }
                                }),
                            Forms\Components\DatePicker::make('tanggal')
                                ->default(now())
                                ->required(),

                            Forms\Components\Select::make('metode_pembayaran')
                                ->options([
                                    'cash' => 'Cash',
                                    'qris' => 'QRIS',
                                ])
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->label('Status Produksi')
                                ->options([
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                ])
                                ->default('selesai')
                                ->reactive()
                                ->disabled(fn ($get) => $get('pelanggan_mode') === 'umum')
                                ->required(),
                            Forms\Components\TextInput::make('nomor_hp_display')
                                ->label('No HP')
                                ->disabled()
                                ->dehydrated(false),

                            Forms\Components\Textarea::make('alamat_display')
                                ->label('Alamat')
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpan(2),

                        ]),
                ]),

            Forms\Components\Section::make('Custom Kue')
            ->schema([

            Forms\Components\Textarea::make('request_custom'),

            Forms\Components\DateTimePicker::make('estimasi_selesai')
                ->label('Estimasi Selesai')
                ->seconds(false)
                ->displayFormat('d M Y, H:i'),

        ])
        ->visible(fn ($get) => $get('pelanggan_mode') === 'custom'),

            // 🧁 DETAIL PRODUK
Forms\Components\Section::make('Detail Produk')
    ->schema([

        Forms\Components\Repeater::make('detail')
            ->relationship()
            ->live()
            ->schema([

                Forms\Components\Grid::make(4)
                    ->schema([

                        Forms\Components\Select::make('produk_id')
                            ->label('Produk')
                            ->relationship('produk', 'nama_kue')
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {

                                $produk = Produk::find($state);

                                if ($produk) {

                                    $harga = (int) $produk->harga_jual;

                                    $set('harga', $harga);
                                    $set('qty', 1);
                                    $set('subtotal', $harga);
                                }
                            }),

                        Forms\Components\Placeholder::make('preview_gambar')
                            ->label('Gambar Produk')
                            ->content(function ($get) {

                                $produk = Produk::find($get('produk_id'));

                                if (!$produk || !$produk->gambar) {
                                    return 'Belum ada gambar';
                                }

                                return new \Illuminate\Support\HtmlString(
                                    "<img src='/storage/{$produk->gambar}'
                                    style='width:120px; border-radius:10px;'>"
                                );
                            }),

                        Forms\Components\TextInput::make('qty')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                $produk = Produk::find($get('produk_id'));

                                if ($produk && $state > $produk->stok) {

                                    Notification::make()
                                        ->title('Stok tidak cukup')
                                        ->body("Sisa stok: {$produk->stok}")
                                        ->danger()
                                        ->send();

                                    $set('qty', $produk->stok);

                                    return;
                                }

                                $set('subtotal', ($get('harga') ?? 0) * $state);
                            }),

                        Forms\Components\Hidden::make('harga'),

                        Forms\Components\Placeholder::make('harga_display')
                            ->label('Harga')
                            ->content(fn ($get) =>
                                'Rp ' . number_format($get('harga') ?? 0, 0, ',', '.')
                            ),

                        Forms\Components\Hidden::make('subtotal'),

                        Forms\Components\Placeholder::make('subtotal_display')
                            ->label('Subtotal')
                            ->content(fn ($get) =>
                                'Rp ' . number_format($get('subtotal') ?? 0, 0, ',', '.')
                            ),

                    ]),

            ])

            ->afterStateHydrated(function ($state, callable $set) {

                $set(
                    'total_harga',
                    collect($state ?? [])->sum('subtotal')
                );
            })

            ->afterStateUpdated(function ($state, callable $set) {

                $total = collect($state ?? [])->sum('subtotal');

                $set('total_harga', $total);
            })

            ->createItemButtonLabel('Tambah Produk')
            ->required(),

]),

// TOTAL
Forms\Components\Section::make('Total')
    ->schema([

        Forms\Components\Hidden::make('total_harga')
            ->dehydrated(true),

        Forms\Components\Placeholder::make('total_display')
            ->label('Total Harga')
            ->content(function ($get) {

                $total = number_format(
                    $get('total_harga') ?? 0,
                    0,
                    ',',
                    '.'
                );

                return new \Illuminate\Support\HtmlString("
                    <div style='
                        background:#fdf2f8;
                        padding:16px;
                        border-radius:12px;
                        border:2px solid #ec4899;
                        font-size:28px;
                        font-weight:bold;
                        color:#be185d;
                        text-align:center;
                    '>
                        Rp {$total}
                    </div>
                ");
            }),

    ]),

    //Totall Harga
        Forms\Components\Section::make('Total')
            ->schema([

                Forms\Components\Hidden::make('total_harga')
                ->dehydrated(true),

                Forms\Components\Placeholder::make('total_display')
                    ->label('Total Harga')
                    ->content(function ($get) {

                        $total = number_format($get('total_harga') ?? 0, 0, ',', '.');

                        return new \Illuminate\Support\HtmlString("
                            <div style='
                                background:#fdf2f8;
                                padding:16px;
                                border-radius:12px;
                                border:2px solid #ec4899;
                                font-size:28px;
                                font-weight:bold;
                                color:#be185d;
                                text-align:center;
                            '>
                                Rp {$total}
                            </div>
                        ");
                    }),

            ]),

    ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\TextColumn::make('no_nota')
                ->label('No Nota'),
            Tables\Columns\TextColumn::make('tanggal')
                ->date('d M Y'),
            Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                ->label('Pelanggan'),
            Tables\Columns\TextColumn::make('metode_pembayaran'),
            Tables\Columns\TextColumn::make('status'),
            Tables\Columns\TextColumn::make('total_harga')
                ->label('Total Harga')
                ->formatStateUsing(
                    fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')
                ),

        ])

        ->actions([

    Tables\Actions\Action::make('selesai')
        ->label('Selesaikan')
        ->color('success')
        ->icon('heroicon-o-check-circle')
        ->visible(fn ($record) => $record->status === 'diproses')
        ->action(function ($record) {

            $record->update([
                'status' => 'selesai',
            ]);

            Notification::make()
                ->title('Pesanan selesai')
                ->success()
                ->send();
        }),

    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make(),
])

->bulkActions([

]);
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
        ];
    }
}