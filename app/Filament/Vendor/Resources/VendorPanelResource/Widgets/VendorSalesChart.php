<?php

namespace App\Filament\Vendor\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VendorSalesChart extends ChartWidget
{
    protected static ?string $heading = 'My Monthly Sales';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $vendorId = Auth::user()->vendor?->id;

        $data = collect(range(0, 5))->map(function ($index) use ($vendorId) {
            $date = Carbon::now()->subMonths($index);

            $count = OrderItem::where('vendor_id', $vendorId)
                ->whereHas('order', fn($q) => $q->where('status', 'completed'))
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('quantity');

            return [
                'month' => $date->format('M'),
                'count' => $count,
            ];
        })->reverse();

        return [
            'datasets' => [
                [
                    'label' => 'Items Sold',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => '#8b5cf6',
                    'borderColor' => '#7c3aed',
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
