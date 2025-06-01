<?php
 
namespace App\Filament\Widgets;

use App\Models\category;
use App\Models\page;
use App\Models\product;
use App\Models\slider;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
 
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Category', category::count()),
            Stat::make('Total Product', product::count()),
            Stat::make('Total Slider', slider::count()),
            Stat::make('Total Pages', page::count()),
        ];
    }
}