<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResiResource\Pages;

use App\Filament\Resources\ResiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResi extends CreateRecord
{
    protected static string $resource = ResiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan customer_id dari sender jika dipilih dari Address Book.
        // (Catatan desain: customer_id di shipments merujuk ke SENDER.
        // Kalau butuh tracking receiver juga, perlu kolom terpisah seperti
        // `receiver_customer_id` di shipments -- TIDAK ditambahkan di sini,
        // diskusikan dulu kalau memang dibutuhkan, jangan tambah sendiri.)
        if (! empty($data['sender_customer_id'])) {
            $data['customer_id'] = $data['sender_customer_id'];
        }

        // Address Book auto-save: sender
        if ($data['save_sender_to_address_book'] ?? false) {
            \App\Models\Customer::firstOrCreate(
                ['phone' => $data['sender_phone']],
                [
                    'name' => $data['sender_name'],
                    'district_id' => $data['origin_district_id'],
                    'address' => $data['sender_address'] ?? null,
                    'type' => 'sender',
                    'created_by' => auth()->id(),
                ]
            );
        }

        // Address Book auto-save: receiver
        if ($data['save_receiver_to_address_book'] ?? false) {
            \App\Models\Customer::firstOrCreate(
                ['phone' => $data['recipient_phone']],
                [
                    'name' => $data['recipient_name'],
                    'district_id' => $data['destination_district_id'],
                    'address' => $data['receiver_address'] ?? null,
                    'type' => 'receiver',
                    'created_by' => auth()->id(),
                ]
            );
        }

        // Hapus virtual fields (agar tidak error SQL — kolom-kolom ini TIDAK ADA di tabel shipments)
        unset($data['sender_customer_id']);
        unset($data['receiver_customer_id']);
        unset($data['save_sender_to_address_book']);
        unset($data['save_receiver_to_address_book']);

        $data['tracking_number'] = app(\App\Services\AwbNumberGeneratorService::class)->generate();
        $data['created_by'] = auth()->id();

        return $data;
    }
}
