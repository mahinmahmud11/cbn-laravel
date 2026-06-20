<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Agency;
use App\Models\ShipItem;
use App\Models\ShipStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    /**
     * Get the stats to display.
     * 
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        if ($isSuperAdmin) {
            return [
                Stat::make('Total Semua Resi', ShipItem::count())
                    ->description('Total paket terdaftar di sistem')
                    ->descriptionIcon('heroicon-m-cube')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('info'),
                
                Stat::make('Total Resi Selesai (Delivered)', $this->countDelivered())
                    ->description('Paket yang sudah sampai tujuan')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->chart([15, 4, 10, 2, 12, 4, 19])
                    ->color('success'),
                
                Stat::make('Total Agen Aktif', Agency::where('status', '1')->count())
                    ->description('Mitra agen aktif saat ini')
                    ->descriptionIcon('heroicon-m-building-office')
                    ->chart([1, 2, 1, 3, 2, 2, 2])
                    ->color('warning'),
            ];
        }

        // Stats for Agency
        $agencyId = (int) $user->store_area;
        $agencyShipmentsQuery = ShipItem::whereHas('user', fn ($q) => $q->where('store_area', $agencyId));

        return [
            Stat::make('Total Resi Saya', (clone $agencyShipmentsQuery)->count())
                ->description('Total paket yang diinput agen Anda')
                ->descriptionIcon('heroicon-m-cube')
                ->chart([3, 1, 5, 2, 8, 3, 10])
                ->color('info'),
            
            Stat::make('Resi Saya (Delivered)', $this->countDelivered($agencyId))
                ->description('Paket agen Anda yang sudah sampai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([2, 5, 3, 8, 4, 9, 12])
                ->color('success'),
            
            Stat::make('Resi Saya (Proses/Transit)', $this->countInTransit($agencyId))
                ->description('Paket agen Anda dalam pengiriman')
                ->descriptionIcon('heroicon-m-truck')
                ->chart([1, 4, 2, 6, 3, 5, 8])
                ->color('warning'),
        ];
    }

    /**
     * Hitung jumlah resi yang status terakhirnya adalah 'done'.
     */
    private function countDelivered(?int $agencyId = null): int
    {
        $query = ShipItem::whereHas('shipStatuses', function ($q) {
            $q->where('id', function ($sub) {
                $sub->select('id')
                    ->from('ship_status')
                    ->whereColumn('item_id', 'ship_items.id')
                    ->latest()
                    ->limit(1);
            })->where('status', 'done');
        });

        if ($agencyId) {
            $query->whereHas('user', fn ($q) => $q->where('store_area', $agencyId));
        }

        return $query->count();
    }

    /**
     * Hitung jumlah resi yang status terakhirnya adalah 'progress' atau 'waiting'.
     */
    private function countInTransit(int $agencyId): int
    {
        return ShipItem::whereHas('user', fn ($q) => $q->where('store_area', $agencyId))
            ->whereHas('shipStatuses', function ($q) {
                $q->where('id', function ($sub) {
                    $sub->select('id')
                        ->from('ship_status')
                        ->whereColumn('item_id', 'ship_items.id')
                        ->latest()
                        ->limit(1);
                })->whereIn('status', ['waiting', 'progress']);
            })
            ->count();
    }
}
