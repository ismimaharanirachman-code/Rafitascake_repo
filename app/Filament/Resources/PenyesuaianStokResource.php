<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenyesuaianStokResource\Pages;
use App\Models\Produk;
use App\Models\PenyesuaianStok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyesuaianStokResource extends Resource
{
    protected static ?string $model            = PenyesuaianStok::class;
    protected static ?string $navigationIcon   = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel  = 'Penyesuaian Stok';
    protected static ?string $navigationGroup  = 'Manajemen Stok';
    protected static ?string $modelLabel       = 'Penyesuaian Stok';
    protected static ?string $pluralModelLabel = 'Penyesuaian Stok';
    protected static ?int    $navigationSort   = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Transaksi')
                ->description('Lengkapi data di bawah ini.')
                ->icon('heroicon-o-document-text')
                ->schema([

                    Forms\Components\TextInput::make('nomor_referensi')
                        ->label('No Transaksi')
                        ->default(fn () => PenyesuaianStok::generateNomorReferensi())
                        ->disabled()
                        ->dehydrated()
                        ->required(),

                    Forms\Components\Hidden::make('tipe')
                        ->default('koreksi'),

                ])
                ->columns(2),

            Forms\Components\Section::make('Produk yang Disesuaikan')
                ->description('Pilih produk yang ingin diperbarui stoknya')
                ->icon('heroicon-o-cube')
                ->schema([

                    Forms\Components\Select::make('produk_id')
                        ->label('Nama Produk')
                        ->placeholder('Cari nama produk...')
                        ->options(Produk::query()->pluck('nama_kue', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set, ?int $state) {
                            if ($state) {
                                $produk = Produk::find($state);
                                $set('stok_sebelum', $produk?->stok ?? 0);
                            }
                        }),

                    Forms\Components\TextInput::make('stok_sebelum')
                        ->label('Stok Tercatat Saat Ini')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0)
                        ->suffix('pcs'),

                ])
                ->columns(2),

            Forms\Components\Section::make('Data Koreksi Stok')
                ->description('Masukkan jumlah stok yang baru')
                ->icon('heroicon-o-calculator')
                ->schema([

                    Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah Stok Sebenarnya')
                        ->helperText('Isi dengan jumlah stok fisik yang ada sekarang')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->suffix('pcs')
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (Set $set, ?int $state) {
                            $set('stok_sesudah', (int) $state);
                        }),

                    Forms\Components\TextInput::make('stok_sesudah')
                        ->label('Stok Setelah Dikoreksi')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0)
                        ->suffix('pcs'),

                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan Koreksi')
                        ->placeholder('Misal: Koreksi Stok Karna Basi, Koreksi Stok Karna Rusak, dll.')
                        ->rows(3)
                        ->columnSpanFull(),

                ])
                ->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Tambahkan koreksi stok pertama dengan menekan tombol di atas')
            ->emptyStateIcon('heroicon-o-arrow-path')
            ->columns([

                Tables\Columns\TextColumn::make('nomor_referensi')
                    ->label('No Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Disalin!')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('produk.nama_kue')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stok_sebelum')
                    ->label('Stok Lama')
                    ->alignCenter()
                    ->suffix(' pcs'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Stok Baru')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => $record->jumlah . ' pcs')
                    ->color('warning'),

                Tables\Columns\TextColumn::make('stok_sesudah')
                    ->label('Hasil Koreksi')
                    ->alignCenter()
                    ->suffix(' pcs'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->keterangan)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('pembuat.name')
                    ->label('Diinput Oleh')
                    ->placeholder('Sistem')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                Tables\Filters\SelectFilter::make('produk_id')
                    ->label('Filter Produk')
                    ->placeholder('Semua Produk')
                    ->options(Produk::query()->pluck('nama_kue', 'id'))
                    ->searchable(),

                Tables\Filters\TrashedFilter::make()
                    ->label('Tampilkan Data')
                    ->placeholder('Data Aktif')
                    ->trueLabel('Termasuk yang Terhapus')
                    ->falseLabel('Hanya yang Aktif'),

            ])
            ->actions([

                Tables\Actions\ViewAction::make()
                    ->label('Detail'),

                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->modalHeading('Pulihkan Data Ini?')
                    ->modalDescription('Data koreksi stok ini akan dikembalikan ke daftar aktif.')
                    ->modalSubmitActionLabel('Pulihkan'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih'),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan yang Dipilih'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPenyesuaianStok::route('/'),
            'create' => Pages\CreatePenyesuaianStok::route('/create'),
            'view'   => Pages\ViewPenyesuaianStok::route('/{record}'),
            'edit'   => Pages\EditPenyesuaianStok::route('/{record}/edit'),
        ];
    }
}