<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel  = 'Manajemen User';
    protected static ?string $modelLabel       = 'User';
    protected static ?string $pluralModelLabel = 'Daftar User';
    protected static ?string $navigationGroup  = 'Sistem & Keamanan';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $navigationIcon   = 'heroicon-o-user-group';

    public static function getModelLabel(): string       { return 'User'; }
    public static function getPluralModelLabel(): string  { return 'Daftar User'; }
    public static function getNavigationLabel(): string   { return 'Manajemen User'; }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->maxLength(50)
                            ->nullable()
                            ->helperText('Kosongkan untuk agen/mitra luar.'),
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2),

                Forms\Components\Section::make('Akses & Afiliasi')
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label('Role Akses')
                            ->relationship('roles', 'name')
                            ->columns(2)
                            ->gridDirection('column'),
                        Forms\Components\Select::make('store_area')
                            ->label('Afiliasi Agen')
                            ->relationship('agency', 'agency_name')
                            ->searchable()
                            ->preload()
                            ->helperText('Kosongkan jika bukan akun agen.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('agency.agency_name')
                    ->label('Lokasi/Agen')
                    ->placeholder('Internal CBN'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 10 ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 10 ? 'Aktif' : 'Non-Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
