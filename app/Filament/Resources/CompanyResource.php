<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $modelLabel = 'Perusahaan';
    protected static ?string $pluralModelLabel = 'Daftar Perusahaan';
    protected static ?string $navigationLabel = 'Perusahaan (Company)';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'administrator']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'administrator']) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'administrator']) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Perusahaan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Perusahaan')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('npwp')
                        ->label('NPWP')
                        ->maxLength(25)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('payment_term_days')
                        ->label('Jangka Waktu Pembayaran (hari)')
                        ->numeric()
                        ->required()
                        ->default(30)
                        ->minValue(1),

                    Forms\Components\Textarea::make('billing_address')
                        ->label('Alamat Penagihan')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('pic_name')
                        ->label('Nama PIC'),

                    Forms\Components\TextInput::make('pic_phone')
                        ->label('No. Telepon PIC')
                        ->tel(),

                    Forms\Components\Hidden::make('created_by')
                        ->default(fn () => auth()->id()),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('pic_name')
                    ->label('PIC')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('payment_term_days')
                    ->label('Termin')
                    ->suffix(' hari')
                    ->sortable(),

                Tables\Columns\TextColumn::make('invoices_count')
                    ->label('Jumlah Invoice')
                    ->counts('invoices')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->hasRole('super_admin')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
