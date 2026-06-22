<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tracking_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tracking_number')
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('No. AWB')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Biaya')
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple()
                    ->after(fn ($livewire) => $this->updateInvoiceTotal($livewire->getOwner())),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->after(fn ($livewire) => $this->updateInvoiceTotal($livewire->getOwner())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->after(fn ($livewire) => $this->updateInvoiceTotal($livewire->getOwner())),
                ]),
            ]);
    }

    protected function updateInvoiceTotal($invoice): void
    {
        $totalShipItems = $invoice->shipItems()->sum('price');
        $totalShipments = $invoice->shipments()->sum('price');
        $invoice->update(['total_amount' => $totalShipItems + $totalShipments]);
    }
}
