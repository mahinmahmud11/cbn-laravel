<?php

namespace App\Filament\Resources\ManifestResource\Pages;

use App\Filament\Resources\ManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManifest extends EditRecord
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $manifest = $this->record;

        if ($manifest->status === 'transit') {
            foreach ($manifest->shipItems as $item) {
                $item->shipStatuses()->create([
                    'status' => 'progress',
                    'track' => 'Diberangkatkan dengan Manifest: ' . $manifest->manifest_number,
                    'created_at' => time(),
                    'user_entry' => auth()->id(),
                ]);
            }
        }
    }
}
