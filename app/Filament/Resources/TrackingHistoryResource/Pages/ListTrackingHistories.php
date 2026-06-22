<?php

namespace App\Filament\Resources\TrackingHistoryResource\Pages;

use App\Filament\Resources\TrackingHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrackingHistories extends ListRecords
{
    protected static string $resource = TrackingHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
