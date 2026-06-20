<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers;
use App\Models\Agency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;
    protected static ?string $navigationLabel = 'Manajemen Agen';
    protected static ?string $navigationGroup = 'Operasional Logistik';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['cityRelation']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('agency_name')
                            ->label('Nama Agen')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('leader')
                            ->label('Kepala Agen / Leader')
                            ->maxLength(35),
                        Forms\Components\Select::make('status')
                            ->label('Status Operasional')
                            ->options([
                                '1' => 'Aktif',
                                '0' => 'Non-Aktif',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Forms\Components\Section::make('Kontak & Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(14),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Agency')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agency_name')
                    ->label('Nama Agen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cityRelation.name')
                    ->label('Kota/Kabupaten')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShipItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
