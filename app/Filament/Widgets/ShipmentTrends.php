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
        // Karena created_at adalah integer (Legacy), kita hitung manual per hari
        $results = [];
        $labels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $startOfDay = (clone $date)->startOfDay()->timestamp;
            $endOfDay = (clone $date)->endOfDay()->timestamp;
            
            $count = ShipItem::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
            
            $results[] = $count;
            $labels[] = $date->format('d M');
        }

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
