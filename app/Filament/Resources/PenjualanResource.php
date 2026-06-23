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
use App\Filament\Exports\PenjualanProdukExporter;
use Filament\Tables\Actions\ExportAction;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $modelLabel = 'Penjualan';

    protected static ?string $pluralModelLabel = 'Penjualan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpanFull()
                    ->extraAttributes([
                         'style' => 'background: #fff5f7; border: 1px solid #fce7f3; border-radius: 24px; padding: 24px;'])
                    ->schema([
                        
                Forms\Components\Placeholder::make('rekomendasi_produk')
                    ->hiddenLabel()
                    ->content(function () {
                $produk = Produk::query()
                    ->join('penjualan_produk', 'produk.id', '=', 'penjualan_produk.produk_id')
                    ->selectRaw('
                        produk.id,
                        produk.nama_kue,
                        produk.gambar,
                        SUM(penjualan_produk.qty) as total_terjual')
                    ->groupBy(
                        'produk.id',
                        'produk.nama_kue',
                        'produk.gambar'
                    )
                    ->orderByDesc('total_terjual')
                    ->limit(3)
                    ->get();

                if ($produk->isEmpty()) {
                    return new \Illuminate\Support\HtmlString("
                        <div style='text-align:center; color: #db2777; font-weight: 600; padding: 20px;'>
                            🌸 Belum ada data penjualan nih~
                        </div>
                    ");
                }

                $html = "
                <div style='
                    background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%);
                    border: 2px solid #fbcfe8;
                    padding: 22px;
                    border-radius: 20px;
                    margin-bottom: 32px;
                    text-align: center;
                    box-shadow: 0 10px 25px -5px rgba(251, 207, 232, 0.5);
                    position: relative;
                '>
                    <span style='position: absolute; top: 12px; left: 20px; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));'>✨</span>
                    <span style='position: absolute; bottom: 12px; right: 20px; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));'>✨</span>

                    <h2 style=\"font-size: 22px; font-weight: 800; color: #9d174d; margin: 0; letter-spacing: -0.02em; font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;\">
                        🎀 Menu Terlaris Rafitas Cake 🎀
                    </h2>
                    <p style='margin: 6px 0 0 0; color: #db2777; font-size: 13px; font-weight: 600; opacity: 0.8;'>
                        Tiga produk paling dicintai dan paling banyak diborong oleh pelanggan setia~
                    </p>
                </div>";

                $html .= "
                <div style='
                    display: flex;
                    gap: 28px;
                    flex-wrap: wrap;
                    justify-content: center;
                '>";

                foreach ($produk as $index => $item) {
                    
                    $isFirst = $index == 0;
                    $cardBg = '#ffffff';
                    $borderColor = $isFirst ? '#f43f5e' : '#fbcfe8';
                    $badgeText = '';
                    $badgeEmoji = '';
                    
                    if ($index == 0) {
                        $badgeText = '👑 Best Seller';
                    } elseif ($index == 1) {
                        $badgeText = '💕 Best Seller';
                    } elseif ($index == 2) {
                        $badgeText = '🌸 Best Seller';

                    }

                    $imageHtml = "";
                    if ($item->gambar) {
                        $imagePath = asset('storage/' . $item->gambar);
                        $imageHtml = "
                            <div style='
                                width: 100%; 
                                height: 100%; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                background: #fff5f7;
                            '>
                                <img src='{$imagePath}' style='
                                    max-width: 100%; 
                                    max-height: 100%; 
                                    object-fit: contain; 
                                    transition: transform 0.6s ease;
                                ' onmouseover=\"this.style.transform='scale(1.05)'\" onmouseout=\"this.style.transform='scale(1)'\" alt='{$item->nama_kue}'>
                            </div>";
                    } else {
                        $imageHtml = "
                        <div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #f43f5e; background: linear-gradient(135deg, #fff5f7 0%, #ffe4e6 100%);'>
                            <span style='font-size: 36px; margin-bottom: 4px; filter: drop-shadow(0 2px 4px rgba(219,39,119,0.1));'>🍰</span>
                            <span style='font-size: 11px; font-weight: 700; letter-spacing: 0.5px;'>Belum ada foto~</span>
                        </div>";
                    }
                    // =========================================================

                    $formattedTerjual = number_format($item->total_terjual, 0, ',', '.');

                    $html .= "
                    <div class='cute-luxury-card' style='
                        width: 245px;
                        background: {$cardBg};
                        border: 2px solid {$borderColor};
                        border-radius: 22px;
                        overflow: hidden;
                        box-shadow: 0 8px 20px -4px rgba(251, 207, 232, 0.4);
                        display: flex;
                        flex-direction: column;
                        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    '>
                        <style>
                            .cute-luxury-card:hover {
                                transform: translateY(-8px) scale(1.02);
                                border-color: #db2777 !important;
                                box-shadow: 0 20px 30px -5px rgba(219, 39, 119, 0.25) !important;
                            }
                        </style>

                        <div style='position: relative; width: 100%; height: 160px; background: #fff5f7; overflow: hidden;'>
                            <div style='
                                position: absolute;
                                top: 12px;
                                left: 12px;
                                background: rgba(255, 255, 255, 0.9);
                                backdrop-filter: blur(8px);
                                color: #db2777;
                                padding: 5px 12px;
                                border-radius: 12px;
                                font-size: 11px;
                                font-weight: 800;
                                border: 1px solid #fbcfe8;
                                box-shadow: 0 4px 10px rgba(219, 39, 119, 0.05);
                                z-index: 5;
                            '>
                                <span style='margin-right: 3px;'>{$badgeEmoji}</span> {$badgeText}
                            </div>
                            {$imageHtml}
                        </div>

                        <div style='padding: 18px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; background: #ffffff;'>
                            <h3 style='
                                font-size: 15px;
                                font-weight: 800;
                                text-align: center;
                                color: #4c0519;
                                margin: 0 0 16px 0;
                                line-height: 1.5;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                height: 44px;
                            '>
                                {$item->nama_kue}
                            </h3>

                            <div style='
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
                                padding: 10px 14px;
                                border-radius: 14px;
                                border: 1px dashed #f43f5e;
                            '>
                                <span style='font-size: 12px; color: #9d174d; font-weight: 700;'>Sudah Terjual</span>
                                <div style='display: flex; align-items: baseline; gap: 2px;'>
                                    <span style='font-size: 18px; color: #e11d48; font-weight: 900; letter-spacing: -0.02em;'>{$formattedTerjual}</span>
                                    <span style='font-size: 11px; font-weight: 700; color: #be123c;'>pcs</span>
                                </div>
                            </div>
                        </div>

                    </div>";
                }

                $html .= "</div>";

                return new \Illuminate\Support\HtmlString($html);
            }),

    ]),

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
            ->headerActions([

   Tables\Actions\Action::make('pdf')
    ->label('Unduh PDF')
    ->icon('heroicon-o-document-arrow-down')
    ->color('danger')
    ->url('/penjualan/pdf')
    ->openUrlInNewTab(),

    ExportAction::make()
        ->exporter(PenjualanProdukExporter::class),

])
            ->actions([
                Tables\Actions\Action::make('bayarQris')
    ->label('Bayar QRIS')
    ->icon('heroicon-o-qr-code')
    ->color('success')

    ->visible(fn ($record) => $record->metode_pembayaran === 'qris')

    ->url(function ($record) {

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $record->id . '-' . time(),
                'gross_amount' => $record->total_harga,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        session()->put('snapToken', $snapToken);

        return url('/midtrans-payment');
    })

    ->openUrlInNewTab(),
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

                Tables\Actions\Action::make('invoice')
                ->label('Invoice')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(fn ($record) => url('/invoice/' . $record->id))
                ->openUrlInNewTab(),

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

