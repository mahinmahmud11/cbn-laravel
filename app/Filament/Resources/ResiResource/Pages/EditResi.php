<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResiResource\Pages;

use App\Filament\Resources\ResiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResi extends EditRecord
{
    protected static string $resource = ResiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
