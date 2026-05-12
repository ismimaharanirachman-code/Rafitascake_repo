<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanBakuResource\Pages;
use App\Models\PembelianBahanBaku;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PembelianBahanBakuResource extends Resource
{
    protected static ?string $model = PembelianBahanBaku::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Pembelian Bahan Baku';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pembelian')
                    ->icon('heroicon-o-document-text')
                    ->description('Masukkan data utama pembelian bahan baku')
                    ->schema([

                        TextInput::make('kode_pembelian')
                            ->label('Kode Pembelian')
                            ->required()
                            ->disabled()
                            ->columnSpanFull()
                            ->default(function () {
                                $last = PembelianBahanBaku::latest()->first();
                                $number = ($last && $last->kode_pembelian)
                                    ? (int) substr($last->kode_pembelian, 2) + 1
                                    : 1;
                                return 'PB' . str_pad($number, 3, '0', STR_PAD_LEFT);
                            }),

                        DatePicker::make('tanggal')
                            ->required(),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'Nama_Supplier')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Detail Pembelian')
                    ->icon('heroicon-o-shopping-bag')
                    ->description('Tambahkan bahan baku yang dibeli')
                    ->schema([

                        Repeater::make('detailPembelian')
                            ->relationship()
                            ->label('')
                            ->columns(7)
                            ->live()
                            ->columnSpanFull()
                            ->collapsed(false)
                            ->addActionLabel('Tambah Bahan')
                            ->schema([
                                Select::make('bahan_baku_id')
                                    ->label('Bahan Baku')
                                    ->options(\App\Models\BahanBaku::pluck('nama_bahan', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->columnSpan(2)
                                    ->helperText('Pilih bahan baku')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $bahan = \App\Models\BahanBaku::find($state);
                                        if ($bahan) {
                                            $set('harga', $bahan->harga);
                                        }
                                    }),

                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->columnSpan(1)
                                    ->afterStateUpdated(function (
                                        $state,
                                        callable $get,
                                        callable $set
                                    ) {
                                        $harga = (int) ($get('harga') ?? 0);

                                        if ($harga && $state) {
                                            $set('subtotal', $state * $harga);
                                        }

                                        $items = $get('../../detailPembelian') ?? [];
                                        $total = collect($items)->sum(
                                            fn($item) =>
                                            (int) ($item['subtotal'] ?? 0));
                                        $set('../../total', $total);
                                    }),

                                TextInput::make('harga')
                                    ->label('Harga')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2)
                                     ->formatStateUsing(fn ($state) => 'Rp ' . number_format((int) $state, 0, ',', '.')),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((int) $state, 0, ',', '.'))
                                    ->afterStateUpdated(function (
                                        callable $get,
                                        callable $set
                                    ) {

                                        $items = $get('../../detailPembelian') ?? [];
                                        $total = collect($items)->sum(
                                            fn($item) =>
                                            (int) ($item['subtotal'] ?? 0));
                                        $set('../../total', $total);
                                    }),
                            ]),
                    ]),

                Section::make('Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->description('Informasi pembayaran transaksi')
                    ->schema([

                        Placeholder::make('grand_total')
                            ->label('Grand Total')
                            ->content(fn($get) => 'Rp ' . number_format(
                                $get('total') ?? 0,0,',','.')),

                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'Cash' => 'Cash',
                                'Transfer' => 'Transfer',])
                            ->required(),

                        Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'Belum Bayar' => 'Belum Bayar',
                                'Lunas' => 'Lunas',])
                            ->default('Belum Bayar')
                            ->required(),

                        TextInput::make('total')
                            ->hidden(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kode_pembelian')
                    ->label('Kode')
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('supplier.Nama_Supplier')
                    ->label('Supplier')
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(
                        fn($state) =>
                        'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                    

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Cash' => 'success',
                        'Transfer' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Lunas' => 'success',
                        'Belum Bayar' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembayaran')
                ->options([
                    'Lunas'=>'Lunas',
                    'Belum Lunas'=>'Belum Lunas',
                ]),
                 Tables\Filters\SelectFilter::make('payment_method')
                 ->label('Metode Pembayaran')
                 ->options([
                    'Cash' => 'Cash',
                    'Transfer' => 'Transfer',]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),]),
            ]);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
        ];
    }
}