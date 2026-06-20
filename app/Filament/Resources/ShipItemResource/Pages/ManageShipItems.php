<?php

namespace App\Filament\Resources\ShipItemResource\Pages;

use App\Filament\Resources\ShipItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShipItems extends ManageRecords
{
    protected static string $resource = ShipItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
