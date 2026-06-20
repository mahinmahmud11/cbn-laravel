<?php

namespace App\Filament\Widgets;

use App\Models\ShipItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonthlyRevenue extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonthRevenue = ShipItem::whereBetween('created_at', [
            now()->startOfMonth()->timestamp,
            now()->endOfMonth()->timestamp
        ])->sum('price');

        $lastMonthRevenue = ShipItem::whereBetween('created_at', [
            now()->subMonth()->startOfMonth()->timestamp,
            now()->subMonth()->endOfMonth()->timestamp
        ])->sum('price');

        $diff = $currentMonthRevenue - $lastMonthRevenue;
        $increase = $lastMonthRevenue > 0 ? ($diff / $lastMonthRevenue) * 100 : 0;

        return [
            Stat::make('Omzet Bulan Ini', 'IDR ' . number_format($currentMonthRevenue, 0, ',', '.'))
                ->description($increase >= 0 ? number_format($increase, 1) . '% naik dari bulan lalu' : number_format(abs($increase), 1) . '% turun dari bulan lalu')
                ->descriptionIcon($increase >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($increase >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]), // Placeholder chart data
        ];
    }
}
