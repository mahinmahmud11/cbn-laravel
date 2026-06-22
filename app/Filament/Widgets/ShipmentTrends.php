<?php

namespace App\Filament\Widgets;

use App\Models\ShipItem;
use Filament\Widgets\ChartWidget;

class ShipmentTrends extends ChartWidget
{
    protected static ?string $heading = 'Tren Pengiriman (30 Hari Terakhir)';
    protected static string $color = 'info';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $cachedData = \Illuminate\Support\Facades\Cache::remember('shipment_trends_30d', 3600, function () {
            $start = now()->subDays(29)->startOfDay();
            $end = now()->endOfDay();

            // Query legacy (integer timestamp)
            $legacyByDate = ShipItem::selectRaw('DATE(FROM_UNIXTIME(created_at)) as date, COUNT(*) as total')
                ->whereBetween('created_at', [$start->timestamp, $end->timestamp])
                ->groupBy('date')
                ->pluck('total', 'date');

            // Query modern (timestamp)
            $newByDate = \App\Models\Shipment::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->pluck('total', 'date');

            $results = [];
            $labels = [];
            
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                $labels[] = $date->format('d M');

                $legacyCount = $legacyByDate->get($dateStr, 0);
                $newCount = $newByDate->get($dateStr, 0);

                $results[] = $legacyCount + $newCount;
            }
            return ['results' => $results, 'labels' => $labels];
        });

        $results = $cachedData['results'];
        $labels = $cachedData['labels'];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Resi Baru',
                    'data' => $results,
                    'fill' => 'start',
                    'tension' => 0.4,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                    'borderColor' => 'rgb(99, 102, 241)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
