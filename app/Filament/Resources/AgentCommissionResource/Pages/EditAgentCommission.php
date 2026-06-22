<?php

namespace App\Filament\Resources\AgentCommissionResource\Pages;

use App\Filament\Resources\AgentCommissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentCommission extends EditRecord
{
    protected static string $resource = AgentCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
