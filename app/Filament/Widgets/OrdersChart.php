<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Support\RawJs;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders per Month';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = collect(range(0, 5))->map(function ($index) {
            $date = Carbon::now()->subMonths($index);

            return [
                'month' => $date->format('M'),
                'count' => Order::where('status', 'completed')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        })->reverse();

        return [
            'datasets' => [
                [
                    'label' => 'Completed Orders',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
