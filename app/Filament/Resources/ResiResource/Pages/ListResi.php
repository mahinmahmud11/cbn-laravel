<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResiResource\Pages;

use App\Filament\Resources\ResiResource;
use Filament\Resources\Pages\ListRecords;

class ListResi extends ListRecords
{
    protected static string $resource = ResiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()->label('Buat Resi Baru'),
        ];
    }
}
