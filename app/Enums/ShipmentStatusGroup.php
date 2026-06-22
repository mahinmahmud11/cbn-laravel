<?php

declare(strict_types=1);

namespace App\Enums;

enum ShipmentStatusGroup: string
{
    case Delivered = 'delivered';
    case InTransit = 'in_transit';
    case Pending = 'pending';

    /**
     * Daftar status mentah (legacy + modern) yang termasuk grup "Delivered".
     */
    public static function deliveredRawStatuses(): array
    {
        return ['done', 'delivered', 'Selesai'];
    }

    /**
     * Daftar status mentah (legacy + modern) yang termasuk grup "In Transit".
     */
    public static function inTransitRawStatuses(): array
    {
        return ['progress', 'in_transit', 'waiting', 'pending', 'picked_up'];
    }

    /**
     * Klasifikasikan status mentah apa pun (dari ship_items ATAU shipments)
     * ke dalam grup standar. Pakai ini di semua tempat, jangan hardcode in_array lagi.
     */
    public static function classify(?string $rawStatus): self
    {
        if ($rawStatus === null) {
            return self::Pending;
        }

        if (in_array($rawStatus, self::deliveredRawStatuses(), true)) {
            return self::Delivered;
        }

        if (in_array($rawStatus, self::inTransitRawStatuses(), true)) {
            return self::InTransit;
        }

        return self::Pending;
    }

    public function label(): string
    {
        return match ($this) {
            self::Delivered => 'Selesai',
            self::InTransit => 'Dalam Perjalanan',
            self::Pending => 'Menunggu',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Delivered => 'success',
            self::InTransit => 'info',
            self::Pending => 'warning',
        };
    }
}
