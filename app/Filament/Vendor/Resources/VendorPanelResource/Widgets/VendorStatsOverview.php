<?php

namespace App\Filament\Vendor\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class VendorStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return [];
        }

        $totalSales = OrderItem::where('vendor_id', $vendor->id)
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total') ?? 0;

        $commissionRate = $vendor->commission_rate ?? 0;
        $totalCommission = ($totalSales * $commissionRate) / 100;

        $netEarnings = $totalSales - $totalCommission;

        return [
            Stat::make('Total Sales', '৳' . number_format($totalSales))
                ->description('Gross sales from completed orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Platform Commission', '- ৳' . number_format($totalCommission))
                ->description("Paid to Admin ({$commissionRate}%)")
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('danger'),

            Stat::make('My Net Earnings', '৳' . number_format($netEarnings))
                ->description('Your actual profit')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([7, 3, 10, 5, 15, 8, 20]),
        ];
    }
}
