<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
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
                    TextInput::make('nama_pembeli')
                        ->label('Nama Pembeli')
                        ->default(function ($record) {
                            logger('DEBUG:: Record User:', [$record->user]);
                            return $record->user?->name ?? 'user null';
                        })
                        ->disabled(),

                    TextInput::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->default(fn ($record) => $record->payment_method ?? '-')
                        ->disabled(),

                    Textarea::make('shipping_address')
                        ->label('Alamat Pengiriman')
                        ->default(fn ($record) => $record->shipping_address ?? '-')
                        ->disabled(),
                ]),

                Section::make('Detail Produk')->schema([
                    TextInput::make('nama_produk')
                        ->label('Nama Produk')
                        ->default(fn ($record) => $record->product?->title ?? '-')
                        ->disabled(),

                    TextInput::make('quantity')
                        ->label('Jumlah')
                        ->disabled(),

                    TextInput::make('total')
                        ->label('Total Harga')
                        ->default(fn ($record) => 
                            $record->product && $record->quantity
                                ? 'Rp ' . number_format($record->product->saleprice * $record->quantity, 0, ',', '.')
                                : '-'
                        )
                        ->disabled(),
                ]),

                Section::make('Status')->schema([
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                        ])
                        ->native(false)
                        ->required(),
                ]),
            ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')->label('Produk'),
                TextColumn::make('user.name')->label('Pembeli'),
                TextColumn::make('quantity')->label('Jumlah')->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')->date()->label('Tanggal'),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('admin.invoice.pdf', $record))
                    ->openUrlInNewTab(),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'product']); // ⬅️ ini wajib agar relasi tersedia di $record
    }
    public static function getNavigationUrl(): string
    {
        return route('filament.admin.resources.orders.index');
    }
    protected static ?string $slug = 'orders';
    protected static ?string $modelLabel = 'Order';
    public static function getRouteName(): string
    {
        return 'filament.resources.orders';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }


}
