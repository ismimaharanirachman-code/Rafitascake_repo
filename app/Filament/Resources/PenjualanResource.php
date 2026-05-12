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
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $modelLabel = 'Penjualan';

    protected static ?string $pluralModelLabel = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make('Informasi Penjualan')
                            ->description('Data pelanggan dan transaksi penjualan')
                            ->icon('heroicon-m-shopping-cart')
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

                                        if ($state === 'umum') {

                                            $set('status', 'selesai');

                                            $umum = \App\Models\Pelanggan::find(1);

                                            if ($umum) {
                                                $set('pelanggan_id', $umum->id_pelanggan);
                                                $set('nomor_hp_display', $umum->nomor_hp);
                                                $set('alamat_display', $umum->alamat);
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

                                Forms\Components\TextInput::make('nomor_hp_display')
                                    ->label('No HP')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\Textarea::make('alamat_display')
                                    ->label('Alamat')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpanFull(),

                            ])->columns(2),

                        Forms\Components\Section::make('Custom Kue')
                            ->description('Request tambahan pelanggan')
                            ->icon('heroicon-m-cake')
                            ->schema([

                                Forms\Components\Textarea::make('request_custom')
                                    ->label('Request Custom'),

                                Forms\Components\DateTimePicker::make('estimasi_selesai')
                                    ->label('Estimasi Selesai')
                                    ->seconds(false)
                                    ->displayFormat('d M Y, H:i'),

                            ])
                            ->visible(fn ($get) => $get('pelanggan_mode') === 'custom'),

                        Forms\Components\Section::make('Detail Produk')
                            ->description('Produk yang dibeli pelanggan')
                            ->icon('heroicon-m-shopping-bag')
                            ->schema([

                                Forms\Components\Repeater::make('detail')
                                    ->relationship()
                                    ->live()
                                    ->schema([

                                        Forms\Components\Grid::make(5)
                                            ->columns(5)
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
                                                    ->label('Preview')
                                                    ->content(function ($get) {

                                                        $produk = Produk::find($get('produk_id'));

                                                        if (!$produk || !$produk->gambar) {
                                                            return 'Belum ada gambar';
                                                        }

                                                        return new \Illuminate\Support\HtmlString(
                                                            "<img src='/storage/{$produk->gambar}'
                                                            style='width:100px;border-radius:10px;'>"
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

                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make('Pembayaran')
                            ->icon('heroicon-m-credit-card')
                            ->schema([

                                Forms\Components\Select::make('metode_pembayaran')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'cash' => 'Tunai',
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
                                    ->disabled(fn ($get) => $get('pelanggan_mode') === 'umum')
                                    ->required(),

                            ]),

                        Forms\Components\Section::make('Total Pembayaran')
                            ->icon('heroicon-m-banknotes')
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
                                                padding:18px;
                                                border-radius:14px;
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

                    ])
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([

                Tables\Actions\Action::make('download_pdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')

                    ->action(function () {

                        $penjualan = \App\Models\Penjualan::all();

                        $pdf = Pdf::loadView('pdf.penjualan', [
                            'penjualan' => $penjualan
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-penjualan.pdf'
                        );
                    }),

            ])

           
->columns([

    Tables\Columns\TextColumn::make('no_nota')
        ->label('No Nota')
        ->searchable()
        ->sortable()
        ->weight('bold'),

    Tables\Columns\TextColumn::make('tanggal')
        ->label('Tanggal')
        ->date('d M Y')
        ->sortable(),

    Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
        ->label('Pelanggan')
        ->searchable(),

    Tables\Columns\TextColumn::make('metode_pembayaran')
        ->label('Metode Pembayaran')
        ->formatStateUsing(fn ($state) =>
            $state === 'cash' ? 'Tunai' : 'QRIS'
        ),

    Tables\Columns\BadgeColumn::make('status')
        ->label('Status')
        ->colors([
            'success' => 'selesai',
            'info' => 'diproses',
        ])
        ->icons([
            'heroicon-m-check-circle' => 'selesai',
            'heroicon-m-clock' => 'diproses',
        ]),

    Tables\Columns\TextColumn::make('total_harga')
        ->label('Total Harga')
        ->money('IDR', locale: 'id')
        ->alignment('right')
        ->weight('bold'),

])

            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ]),

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
                Tables\Actions\ViewAction::make()
                ->label('')
                ->icon('heroicon-o-eye')
                ->color('gray'),
                
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('kirimEmail')
                    ->label('Kirim Email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()

                    ->action(function ($record) {

                        \App\Http\Controllers\PengirimanEmailController
                            ::prosesKirimEmail($record->id);

                        Notification::make()
                            ->title('Email berhasil dikirim')
                            ->success()
                            ->send();
                    }),

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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
        ];
    }
}

