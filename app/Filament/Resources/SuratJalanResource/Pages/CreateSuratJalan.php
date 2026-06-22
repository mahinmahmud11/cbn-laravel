<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuratJalanResource\Pages;

use App\Filament\Resources\SuratJalanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratJalan extends CreateRecord
{
    protected static string $resource = SuratJalanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate nomor surat jalan: SJ-YYMM-NNNNN
        $period = now()->format('ym');
        $last = \App\Models\SuratJalan::whereRaw("nomor LIKE ?", ["SJ-{$period}-%"])
            ->count();
        $data['nomor']      = sprintf('SJ-%s-%05d', $period, $last + 1);
        $data['created_by'] = auth()->id();
        return $data;
    }
}
