<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Group::make([
                Section::make('Informasi Pembeli')->schema([
                    TextInput::make('user.name')
                        ->label('Nama Pembeli')
                        ->disabled(),
                    TextInput::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->default(fn ($record) => $record->payment_method ?? '-')
                        ->disabled(),
                    Textarea::make('shipping_address')
                        ->label('Alamat Pengiriman')
                        ->default(fn ($record) => $record->shipping_address ?? '-')
                        ->disabled(),
                    TextInput::make('shipping_method')
                        ->label('Metode Pengiriman')
                        ->default(fn ($record) => $record->shipping_method ?? '-')
                        ->disabled(),
                ])->columns(2),

                Section::make('Detail Produk Pesanan')->schema([
                    Textarea::make('product_details')
                        ->label('Detail Produk')
                        ->default(function ($record) {
                            $details = '';
                            if ($record && $record->items) {
                                foreach ($record->items as $item) {
                                    $productTitle = $item->product->title ?? 'Produk Tidak Ditemukan';
                                    $price = number_format($item->price_at_purchase, 0, ',', '.');
                                    $subtotal = number_format($item->price_at_purchase * $item->quantity, 0, ',', '.');
                                    $details .= "- {$productTitle} (x{$item->quantity}) @ Rp{$price} = Rp{$subtotal}\n";
                                }
                            }
                            return $details ?: '-';
                        })
                        ->disabled()
                        ->rows(5)
                        ->columnSpanFull(),
                ])->columns(1),

                Section::make('Ringkasan & Status')->schema([
                    TextInput::make('order_number')
                        ->label('Nomor Pesanan')
                        ->disabled(),
                    TextInput::make('total_amount')
                        ->label('Total Harga Pesanan')
                        ->default(fn ($record) => 'Rp ' . number_format($record->total_amount, 0, ',', '.'))
                        ->disabled(),
                    Select::make('status')
                        ->label('Status Order')
                        ->options([
                            'pending' => 'Pending',
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            'kadaluarsa' => 'Kadaluarsa',
                        ])
                        ->native(false)
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('order_number')->label('No. Pesanan')->searchable()->sortable(), // <--- DIKOMENTARI/DIHAPUS
                TextColumn::make('user.name')
                    ->label('Pembeli')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_summary')
                    ->label('Produk')
                    ->formatStateUsing(function (Order $record) {
                        if ($record->items->isEmpty()) {
                            return 'Tidak ada produk';
                        }
                        return $record->items->map(function ($item) {
                            return ($item->product->title ?? 'Produk Tidak Ditemukan') . ' (x' . $item->quantity . ')';
                        })->implode(', ');
                    })
                    ->limit(50)
                    ->tooltip(function (Order $record) {
                        return $record->items->map(function ($item) {
                            return ($item->product->title ?? 'Produk Tidak Ditemukan') . ' (x' . $item->quantity . ')';
                        })->implode("\n");
                    }),
                TextColumn::make('total_amount')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        'kadaluarsa' => 'Kadaluarsa',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        'kadaluarsa' => 'Kadaluarsa',
                    ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(), // <--- DIKOMENTARI/DIHAPUS
                Tables\Actions\Action::make('packing_slip_pdf') // <--- NAMA BARU UNTUK ACTION PACKING SLIP
                    ->label('Cetak Packing Slip') // Label baru
                    ->icon('heroicon-o-printer') // Ikon printer
                    ->url(fn (Order $record) => route('admin.packing_slip.pdf', $record)) // <--- ROUTE BARU
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListOrders::route('/'),
            // Hapus 'create' dan 'edit' jika admin hanya mengedit status dari tabel dan tidak ada form detail terpisah
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'items.product']);
    }
}