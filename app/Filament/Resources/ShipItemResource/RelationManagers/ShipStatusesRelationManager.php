<?php

declare(strict_types=1);

namespace App\Filament\Resources\ShipItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipStatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'shipStatuses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Status Pengiriman')
                    ->options([
                        'waiting' => 'Waiting',
                        'progress' => 'In Progress',
                        'done' => 'Done / Delivered',
                        'cancelled' => 'Cancelled',
                        'return' => 'Returned',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Textarea::make('track')
                    ->label('Keterangan Lokasi/Tracking')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('pod_file')
                    ->label('Foto Bukti Pengiriman (POD)')
                    ->image()
                    ->disk('local')
                    ->directory('pods')
                    ->visibility('private')
                    ->required(fn (Forms\Get $get) => $get('status') === 'done')
                    ->helperText('Wajib diunggah jika status adalah Done / Delivered.')
                    ->dehydrated(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting' => 'warning',
                        'progress' => 'primary',
                        'done' => 'success',
                        'cancelled', 'return' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('track')
                    ->label('Keterangan Tracking')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('pod.file_path')
                    ->label('Foto POD')
                    ->disk('local')
                    ->visibility('private')
                    ->circular()
                    ->url(fn ($record) => $record->pod ? route('admin.pods.show', ['filename' => basename($record->pod->file_path)]) : null),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Update')
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::createFromTimestamp($state)->format('d/m/Y H:i') : '-')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_at'] = time();
                        $data['user_entry'] = auth()->id();
                        return $data;
                    })
                    ->after(function (array $data, \App\Models\ShipStatus $record) {
                        if (isset($data['pod_file'])) {
                            $record->pod()->create([
                                'file_path' => $data['pod_file'],
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('super_admin')),
                ]),
            ]);
    }
}
