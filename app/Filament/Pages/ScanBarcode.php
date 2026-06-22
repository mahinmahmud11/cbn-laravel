<?php

namespace App\Filament\Pages;

use App\Models\Shipment;
use App\Models\TrackingHistory;
use App\Models\AgentCommission;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ScanBarcode extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.pages.scan-barcode';

    protected static ?string $navigationLabel = 'Scan Barcode';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Scan Barcode Paket';

    public ?string $tracking_number = '';

    public string $scanStatus = 'TRANSIT';

    public $photo;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tracking_number')
                    ->label('Nomor Resi / Barcode')
                    ->placeholder('Tempelkan kursor di sini dan tembak barcode...')
                    ->autofocus()
                    ->required(),
            ]);
    }

    public function processScan(): void
    {
        $this->validate([
            'tracking_number' => 'required',
        ]);

        try {
            DB::transaction(function () {
                $shipment = Shipment::where('tracking_number', $this->tracking_number)->first();

                if (!$shipment) {
                    throw new \Exception("Resi dengan nomor {$this->tracking_number} tidak ditemukan.");
                }

                $photoPath = null;
                if ($this->photo) {
                    $photoPath = $this->photo->store('proofs', 'public');
                }

                // 1. Tambah Riwayat Tracking
                TrackingHistory::create([
                    'shipment_id' => $shipment->id,
                    'status' => $this->scanStatus,
                    'location' => 'Fasilitas Agen',
                    'photo_proof' => $photoPath,
                    'updated_by' => auth()->id(),
                ]);

                // 2. Tambah Komisi Agen
                AgentCommission::create([
                    'agent_id' => auth()->id(),
                    'shipment_id' => $shipment->id,
                    'amount' => 1500,
                    'status' => 'pending',
                ]);

                // 3. Update Status Shipment
                $shipment->update([
                    'current_status' => $this->scanStatus,
                ]);
            });

            Notification::make()
                ->title('Berhasil!')
                ->body("Paket {$this->tracking_number} telah diproses ke status {$this->scanStatus}.")
                ->success()
                ->send();

            $this->tracking_number = '';
            $this->photo = null;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
