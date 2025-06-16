<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Category; // Import Category model
use App\Models\Product; // Import Product model
use App\Models\Slider;   // Import Slider model
use App\Models\Page;     // Import Page model
use App\Models\Order;    // <--- IMPORT ORDER MODEL

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Category', Category::count())
                ->description('Jumlah kategori produk')
                ->color('info'),
            Stat::make('Total Product', Product::count())
                ->description('Jumlah semua produk')
                ->color('success'),
            Stat::make('Total Slider', Slider::count())
                ->description('Jumlah slider aktif')
                ->color('warning'),
            // Ubah ini menjadi Total Pesanan
            Stat::make('Total Pesanan', Order::count()) // <--- UBAH INI
                ->description('Jumlah seluruh pesanan')
                ->color('primary'),
        ];
    }
}