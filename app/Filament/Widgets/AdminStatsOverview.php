<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $totalOrders = Order::where('status', 'completed')->count();
        $adminEarnings = OrderItem::whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->with('vendor')
            ->get()
            ->sum(function ($item) {
                $commissionRate = $item->vendor->commission_rate ?? 0;
                return ($item->price * $item->quantity) * ($commissionRate / 100);
            });

        return [
            Stat::make('Total Revenue (GMV)', '৳' . number_format($totalRevenue))
                ->description('Total money flow')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Admin Wallet (Net Profit)', '৳' . number_format($adminEarnings))
                ->description('Your commission earnings')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([15, 4, 10, 2, 12, 4, 12]),

            Stat::make('Total Confirmed Orders', $totalOrders)
                ->description('Orders marked as completed')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
        ];
    }
}
