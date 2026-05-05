<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Collection;
use Livewire\Component;

class TariffCalculator extends Component
{
    // Origin
    public $originProvince = '';
    public $originRegency = '';
    public $originDistrict = '';

    // Destination
    public $destinationProvince = '';
    public $destinationRegency = '';
    public $destinationDistrict = '';

    public $weight = 1;

    // Collections for dropdowns
    public Collection $provinces;
    public Collection $originRegencies;
    public Collection $originDistricts;
    public Collection $destinationRegencies;
    public Collection $destinationDistricts;

    public $tariffResult = null;

    public function mount()
    {
        $this->provinces = Province::orderBy('name')->get();
        $this->originRegencies = collect();
        $this->originDistricts = collect();
        $this->destinationRegencies = collect();
        $this->destinationDistricts = collect();
    }

    public function updatedOriginProvince($value)
    {
        $this->originRegencies = Regency::where('province_id', $value)->orderBy('name')->get();
        $this->originRegency = '';
        $this->originDistrict = '';
        $this->originDistricts = collect();
    }

    public function updatedOriginRegency($value)
    {
        $this->originDistricts = District::where('regency_id', $value)->orderBy('name')->get();
        $this->originDistrict = '';
    }

    public function updatedDestinationProvince($value)
    {
        $this->destinationRegencies = Regency::where('province_id', $value)->orderBy('name')->get();
        $this->destinationRegency = '';
        $this->destinationDistrict = '';
        $this->destinationDistricts = collect();
    }

    public function updatedDestinationRegency($value)
    {
        $this->destinationDistricts = District::where('regency_id', $value)->orderBy('name')->get();
        $this->destinationDistrict = '';
    }

    public function calculate()
    {
        $this->validate([
            'originDistrict' => 'required',
            'destinationDistrict' => 'required',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $this->tariffResult = null;

        // 1. Dapatkan kode rute Asal
        $originRoute = \App\Models\RouteDestination::where('district_cbn', $this->originDistrict)->first();
        
        // 2. Dapatkan kode rute Tujuan
        $destRoute = \App\Models\RouteDestination::where('district_cbn', $this->destinationDistrict)->first();

        if (!$originRoute || !$destRoute) {
            $this->addError('destinationDistrict', 'Maaf, rute pengiriman untuk wilayah tersebut saat ini belum tersedia (Kode Rute tidak ditemukan).');
            return;
        }

        // 3. Cari tarif di lp_tariff berdasarkan hometown_id dan destination_id
        $tariffs = \App\Models\Tariff::where('hometown_id', $originRoute->district_code)
            ->where('destination_id', $destRoute->district_code)
            ->where('normal_price', '>', 0)
            ->get();

        if ($tariffs->isEmpty()) {
            $this->addError('destinationDistrict', 'Maaf, rute pengiriman untuk wilayah tersebut saat ini belum tersedia.');
            return;
        }

        $origin = District::with('regency.province')->find($this->originDistrict);
        $dest = District::with('regency.province')->find($this->destinationDistrict);

        $services = $tariffs->map(function($t) {
            return [
                'name' => $t->category,
                'price' => (float) $t->normal_price * $this->weight,
                'etd' => $this->getEtdByService($t->category)
            ];
        })->toArray();

        $this->tariffResult = [
            'origin' => "{$origin->name}, {$origin->regency->name}",
            'destination' => "{$dest->name}, {$dest->regency->name}",
            'weight' => $this->weight,
            'services' => $services
        ];
    }

    /**
     * Helper untuk estimasi waktu pengiriman berdasarkan kategori layanan.
     */
    private function getEtdByService(string $category): string
    {
        return match (strtoupper($category)) {
            'JAGOPACK' => '2-4 Hari',
            'REGULER', 'REGPACK' => '2-3 Hari',
            'ONEPACK', 'VIPPACK' => '1-2 Hari',
            'JUMBOPACK' => '3-5 Hari',
            default => '2-5 Hari',
        };
    }

    public function render()
    {
        return view('livewire.tariff-calculator');
    }
}
