<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Company;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['company_id'])) {
            $company = Company::find($data['company_id']);

            if ($company) {
                // Snapshot — sesuai Rule 16, jangan andalkan JOIN live ke companies
                // saat invoice sudah tercetak dan data company berubah di kemudian hari.
                $data['customer_name'] = $company->name;
                $data['customer_address'] = $company->billing_address;
                $data['due_date'] = now()->addDays($company->payment_term_days)->toDateString();
            }
        }

        return $data;
    }
}
