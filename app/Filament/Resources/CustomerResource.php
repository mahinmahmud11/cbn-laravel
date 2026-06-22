<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Pelanggan Tetap';
    protected static ?int $navigationSort = 20;
    protected static ?string $modelLabel = 'Pelanggan';
    protected static ?string $pluralModelLabel = 'Pelanggan Tetap';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator', 'agent']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator', 'agent']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Pelanggan')
                            ->options([
                                'sender' => 'Pengirim Saja',
                                'receiver' => 'Penerima Saja',
                                'both' => 'Keduanya',
                            ])
                            ->default('both')
                            ->required(),
                        Forms\Components\Select::make('company_id')
                            ->label('Perusahaan (Opsional)')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Isi jika kontak ini mewakili perusahaan korporat yang ditagih lewat invoicing berkala.'),
                    ]),
                
                Forms\Components\Section::make('Alamat Lengkap')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Jalan / Detail')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('province_id')
                            ->label('Provinsi')
                            ->options(Province::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('regency_id', null);
                                $set('district_id', null);
                                $set('village_id', null);
                            }),

                        Forms\Components\Select::make('regency_id')
                            ->label('Kota/Kabupaten')
                            ->options(function (callable $get) {
                                $province = $get('province_id');
                                if (!$province) {
                                    return [];
                                }
                                return Regency::where('province_id', $province)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->disabled(fn (callable $get) => !$get('province_id'))
                            ->afterStateUpdated(function (callable $set) {
                                $set('district_id', null);
                                $set('village_id', null);
                            }),

                        Forms\Components\Select::make('district_id')
                            ->label('Kecamatan')
                            ->options(function (callable $get) {
                                $regency = $get('regency_id');
                                if (!$regency) {
                                    return [];
                                }
                                return District::where('regency_id', $regency)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->disabled(fn (callable $get) => !$get('regency_id'))
                            ->afterStateUpdated(function (callable $set) {
                                $set('village_id', null);
                            }),

                        Forms\Components\Select::make('village_id')
                            ->label('Desa/Kelurahan')
                            ->options(function (callable $get) {
                                $district = $get('district_id');
                                if (!$district) {
                                    return [];
                                }
                                return Village::where('district_id', $district)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->disabled(fn (callable $get) => !$get('district_id')),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Telepon')->searchable(),
                Tables\Columns\TextColumn::make('district.name')->label('Kecamatan')->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => 'sender',
                        'success' => 'receiver',
                        'warning' => 'both',
                    ]),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator.name')->label('Dibuat Oleh'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Buat')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
