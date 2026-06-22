<?php

namespace App\Livewire;

use App\Models\Shipment;
use Livewire\Component;
use Livewire\Attributes\Url;

class PublicTracking extends Component
{
    #[Url(as: 'resi')]
    public string $trackingNumber = '';
    
    public $shipment = null;
    public bool $searched = false;

    public function mount(): void
    {
        if (!empty($this->trackingNumber)) {
            $this->track();
        }
    }

    public function track(): void
    {
        $this->searched = true;
        
        if (empty($this->trackingNumber)) {
            $this->shipment = null;
            return;
        }

        // Coba cari di arsitektur baru (Shipment)
        $newShipment = Shipment::with(['trackingHistories' => function ($query) {
            $query->latest();
        }])->where('tracking_number', $this->trackingNumber)->first();

        if ($newShipment) {
            $this->shipment = (object) [
                'is_legacy' => false,
                'tracking_number' => $newShipment->tracking_number,
                'current_status' => $newShipment->current_status,
                'customer_name' => $newShipment->customer_name,
                'recipient_name' => $newShipment->recipient_name,
                'origin_city' => $newShipment->origin_city,
                'destination_city' => $newShipment->destination_city,
                'kilogram' => $newShipment->weight_kg,
                'pieces' => $newShipment->pieces,
                'package_type' => $newShipment->package_type,
                'histories' => $newShipment->trackingHistories->map(fn($h) => (object)[
                    'date' => $h->created_at,
                    'status' => $h->status,
                    'location' => $h->location,
                    'description' => $h->description
                ])
            ];
            return;
        }

        // Fallback: Cari di legacy (ShipItem)
        $legacyShipment = \App\Models\ShipItem::with(['shipStatuses' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'originDistrict', 'destinationDistrict'])->where('awb', $this->trackingNumber)->first();

        if ($legacyShipment) {
            $this->shipment = (object) [
                'is_legacy' => true,
                'tracking_number' => $legacyShipment->awb,
                'current_status' => $legacyShipment->status == '1' ? 'Aktif' : 'Selesai',
                'customer_name' => $legacyShipment->sender_name,
                'recipient_name' => $legacyShipment->recipient_name,
                'origin_city' => $legacyShipment->originDistrict?->name ?? '-',
                'destination_city' => $legacyShipment->destinationDistrict?->name ?? '-',
                'kilogram' => $legacyShipment->kilogram,
                'pieces' => $legacyShipment->pieces,
                'package_type' => $legacyShipment->package_type,
                'histories' => $legacyShipment->shipStatuses->map(fn($h) => (object)[
                    'date' => \Carbon\Carbon::createFromTimestamp($h->created_at),
                    'status' => $h->status,
                    'location' => $h->location,
                    'description' => null
                ])
            ];
            return;
        }

        $this->shipment = null;
    }

    public function render()
    {
        return view('livewire.public-tracking');
    }
}
