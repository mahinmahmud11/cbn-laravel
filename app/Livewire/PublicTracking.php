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

        $this->shipment = Shipment::with(['trackingHistories' => function ($query) {
            $query->latest();
        }])->where('tracking_number', $this->trackingNumber)->first();
    }

    public function render()
    {
        return view('livewire.public-tracking');
    }
}
