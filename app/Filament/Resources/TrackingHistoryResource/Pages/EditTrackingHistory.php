<?php

namespace App\Filament\Resources\TrackingHistoryResource\Pages;

use App\Filament\Resources\TrackingHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrackingHistory extends EditRecord
{
    protected static string $resource = TrackingHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
