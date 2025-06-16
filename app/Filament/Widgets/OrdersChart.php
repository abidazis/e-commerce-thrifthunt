<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order; // Import Order model
use Carbon\Carbon; // Import Carbon for date handling

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Total Pesanan Bulanan';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = $this->getOrdersPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => array_values($data),
                    'backgroundColor' => '#2563eb',
                    'borderColor' => '#2563eb',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    private function getOrdersPerMonth(): array
    {
        // Ambil data untuk 6 bulan terakhir
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[Carbon::now()->subMonths($i)->format('M Y')] = 0;
        }

        // Ambil data pesanan dari database
        $orders = Order::select(
                \DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                \DB::raw('COUNT(*) as total_orders')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderByRaw('min(created_at) asc')
            ->get();

        // Gabungkan data dari database ke array bulan
        foreach ($orders as $order) {
            $months[$order->month] = $order->total_orders;
        }

        return $months;
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'legend' => [
                    'display' => false,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}