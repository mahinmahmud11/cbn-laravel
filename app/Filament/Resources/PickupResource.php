<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PickupResource\Pages;
use App\Filament\Resources\PickupResource\RelationManagers;
use App\Models\Pickup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PickupResource extends Resource
{
    protected static ?string $model           = Pickup::class;
    protected static ?string $navigationLabel  = 'Request Penjemputan';
    protected static ?string $modelLabel       = 'Penjemputan';
    protected static ?string $pluralModelLabel = 'Daftar Penjemputan';
    protected static ?string $navigationGroup  = 'Transaksi';
    protected static ?int    $navigationSort   = 6;
    protected static ?string $navigationIcon   = 'heroicon-o-truck';

    public static function getModelLabel(): string       { return 'Penjemputan'; }
    public static function getPluralModelLabel(): string  { return 'Daftar Penjemputan'; }
    public static function getNavigationLabel(): string   { return 'Request Penjemputan'; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Request')
                    ->schema([
                        Forms\Components\TextInput::make('pickup_number')
                            ->label('No. Request')
                            ->default('PKP-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))))
                            ->readOnly()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending (Menunggu)',
                                'assigned' => 'Assigned (Kurir Ditugaskan)',
                                'picked_up' => 'Picked Up (Selesai Jemput)',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                    ])->columns(2),

                Forms\Components\Section::make('Data Pelanggan & Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required(),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('No. Telepon / WA')
                            ->tel()
                            ->required(),
                        Forms\Components\DateTimePicker::make('pickup_time')
                            ->label('Waktu Penjemputan')
                            ->required()
                            ->default(now()->addHours(2)),
                        Forms\Components\TextInput::make('estimated_items')
                            ->label('Estimasi Jumlah Barang')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Textarea::make('pickup_address')
                            ->label('Alamat Lengkap Penjemputan')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Penugasan Kurir')
                    ->schema([
                        Forms\Components\Select::make('courier_id')
                            ->label('Pilih Kurir')
                            ->relationship('courier', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pickup_number')
                    ->label('No. Request')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pickup_time')
                    ->label('Waktu Jemput')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'assigned' => 'warning',
                        'picked_up' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('courier.name')
                    ->label('Kurir')
                    ->placeholder('Belum Ditugaskan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'picked_up' => 'Picked Up',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPickups::route('/'),
            'create' => Pages\CreatePickup::route('/create'),
            'edit' => Pages\EditPickup::route('/{record}/edit'),
        ];
    }
}
