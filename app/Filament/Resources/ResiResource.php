<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ResiResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResiResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationLabel   = 'Daftar Resi';
    protected static ?string $modelLabel        = 'Resi';
    protected static ?string $pluralModelLabel  = 'Daftar Resi';
    protected static ?string $navigationIcon    = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $slug = 'resi';

    public static function getModelLabel(): string      { return 'Resi'; }
    public static function getPluralModelLabel(): string { return 'Daftar Resi'; }
    public static function getNavigationLabel(): string  { return 'Daftar Resi'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Nomor Resi')
                ->schema([
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('No. AWB')
                        ->disabled()
                        ->dehydrated()
                        ->placeholder('Dibuat otomatis saat simpan')
                        ->helperText('Format: YYMM + 5 digit urut. Contoh: 260600001')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Informasi Pengirim')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('sender_customer_id')
                        ->label('Cari dari Buku Kontak (Opsional)')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            \App\Models\Customer::with('company')
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orderBy('name')
                                ->limit(10)
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => $c->company ? "{$c->name} ({$c->company->name})" : $c->name,
                                ])
                        )
                        ->getOptionLabelUsing(function ($value): ?string {
                            $customer = \App\Models\Customer::with('company')->find($value);
                            if (! $customer) {
                                return null;
                            }
                            return $customer->company ? "{$customer->name} ({$customer->company->name})" : $customer->name;
                        })
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($customer = \App\Models\Customer::with('company')->find($state)) {
                                $set('sender_name', $customer->name);
                                $set('sender_phone', $customer->phone);
                                $set('origin_district_id', $customer->district_id);
                                if ($customer->district) {
                                    $set('origin_regency_id', $customer->district->regency_id);
                                    $set('origin_province_id', $customer->district->regency->province_id);
                                }
                            }
                        }),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('sender_name')->label('Nama Pengirim')->required(),
                        Forms\Components\TextInput::make('sender_phone')->label('No. Telepon')->required()->numeric(),
                    ]),
                    
                    Forms\Components\Placeholder::make('sender_company_info')
                        ->label('Perusahaan Terkait')
                        ->content(function (callable $get) {
                            $customerId = $get('sender_customer_id');
                            if (! $customerId) {
                                return '—';
                            }
                            $customer = \App\Models\Customer::with('company')->find($customerId);
                            return $customer?->company?->name ?? 'Tidak terhubung ke perusahaan manapun';
                        })
                        ->visible(fn (callable $get) => filled($get('sender_customer_id'))),

                    Forms\Components\Select::make('origin_district_id')
                        ->label('Cari Kecamatan')
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            \App\Models\District::with('regency.province')
                                ->where('name', 'like', "%{$search}%")
                                ->limit(20)
                                ->get()
                                ->mapWithKeys(fn ($d) => [$d->id => "{$d->name}, {$d->regency->name}, {$d->regency->province->name}"])
                                ->toArray()
                        )
                        ->getOptionLabelUsing(fn ($value) => \App\Models\District::find($value)?->name)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($district = \App\Models\District::with('regency')->find($state)) {
                                $set('origin_regency_id', $district->regency_id);
                                $set('origin_province_id', $district->regency->province_id);
                            } else {
                                $set('origin_regency_id', null);
                                $set('origin_province_id', null);
                            }
                            self::updatePrice($set, $get);
                        }),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('origin_regency_id')
                            ->label('Kota/Kabupaten')
                            ->live()
                            ->options(fn (callable $get) =>
                                $get('origin_province_id') ? \App\Services\GeoService::regencies($get('origin_province_id')) : []
                            )
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('origin_province_id')
                            ->label('Provinsi')
                            ->live()
                            ->options(fn () => \App\Services\GeoService::provinces())
                            ->disabled()
                            ->dehydrated(),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('origin_village_id')
                            ->label('Desa/Kelurahan (Opsional)')
                            ->options(fn (callable $get) =>
                                $get('origin_district_id') ? \App\Services\GeoService::villages($get('origin_district_id')) : []
                            ),
                        Forms\Components\TextInput::make('origin_postal_code')->label('Kode Pos (Opsional)'),
                    ]),

                    Forms\Components\Textarea::make('sender_address')->label('Alamat Detail (Opsional)')->columnSpanFull(),

                    Forms\Components\Checkbox::make('save_sender_to_address_book')
                        ->label('Simpan pengirim ini ke Buku Kontak Tetap')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Informasi Penerima')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('receiver_customer_id')
                        ->label('Cari dari Buku Kontak (Opsional)')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            \App\Models\Customer::with('company')
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orderBy('name')
                                ->limit(10)
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => $c->company ? "{$c->name} ({$c->company->name})" : $c->name,
                                ])
                        )
                        ->getOptionLabelUsing(function ($value): ?string {
                            $customer = \App\Models\Customer::with('company')->find($value);
                            if (! $customer) {
                                return null;
                            }
                            return $customer->company ? "{$customer->name} ({$customer->company->name})" : $customer->name;
                        })
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($customer = \App\Models\Customer::with('company')->find($state)) {
                                $set('recipient_name', $customer->name);
                                $set('recipient_phone', $customer->phone);
                                $set('destination_district_id', $customer->district_id);
                                if ($customer->district) {
                                    $set('destination_regency_id', $customer->district->regency_id);
                                    $set('destination_province_id', $customer->district->regency->province_id);
                                }
                            }
                        }),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('recipient_name')->label('Nama Penerima')->required(),
                        Forms\Components\TextInput::make('recipient_phone')->label('No. Telepon')->required()->numeric(),
                    ]),
                    
                    Forms\Components\Placeholder::make('receiver_company_info')
                        ->label('Perusahaan Terkait')
                        ->content(function (callable $get) {
                            $customerId = $get('receiver_customer_id');
                            if (! $customerId) {
                                return '—';
                            }
                            $customer = \App\Models\Customer::with('company')->find($customerId);
                            return $customer?->company?->name ?? 'Tidak terhubung ke perusahaan manapun';
                        })
                        ->visible(fn (callable $get) => filled($get('receiver_customer_id'))),

                    Forms\Components\Select::make('destination_district_id')
                        ->label('Cari Kecamatan')
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            \App\Models\District::with('regency.province')
                                ->where('name', 'like', "%{$search}%")
                                ->limit(20)
                                ->get()
                                ->mapWithKeys(fn ($d) => [$d->id => "{$d->name}, {$d->regency->name}, {$d->regency->province->name}"])
                                ->toArray()
                        )
                        ->getOptionLabelUsing(fn ($value) => \App\Models\District::find($value)?->name)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if ($district = \App\Models\District::with('regency')->find($state)) {
                                $set('destination_regency_id', $district->regency_id);
                                $set('destination_province_id', $district->regency->province_id);
                            } else {
                                $set('destination_regency_id', null);
                                $set('destination_province_id', null);
                            }
                            self::updatePrice($set, $get);
                        }),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('destination_regency_id')
                            ->label('Kota/Kabupaten')
                            ->live()
                            ->options(fn (callable $get) =>
                                $get('destination_province_id') ? \App\Services\GeoService::regencies($get('destination_province_id')) : []
                            )
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('destination_province_id')
                            ->label('Provinsi')
                            ->live()
                            ->options(fn () => \App\Services\GeoService::provinces())
                            ->disabled()
                            ->dehydrated(),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('destination_village_id')
                            ->label('Desa/Kelurahan (Opsional)')
                            ->options(fn (callable $get) =>
                                $get('destination_district_id') ? \App\Services\GeoService::villages($get('destination_district_id')) : []
                            ),
                        Forms\Components\TextInput::make('destination_postal_code')->label('Kode Pos (Opsional)'),
                    ]),

                    Forms\Components\Textarea::make('receiver_address')->label('Alamat Detail (Opsional)')->columnSpanFull(),

                    Forms\Components\Checkbox::make('save_receiver_to_address_book')
                        ->label('Simpan penerima ini ke Buku Kontak Tetap')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Detail Paket & Layanan')
                ->schema([
                    Forms\Components\TextInput::make('pieces')
                        ->label('Jumlah Koli')
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->minValue(1),
                    Forms\Components\TextInput::make('weight_kg')
                        ->label('Berat (Kg)')
                        ->numeric()
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
                    Forms\Components\TextInput::make('dimension')
                        ->label('Dimensi (P×L×T)')
                        ->placeholder('30x20x15')
                        ->maxLength(20)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::updatePrice($set, $get)),
                    Forms\Components\Select::make('package_type')
                        ->label('Jenis Layanan')
                        ->options([
                            'regular' => 'Reguler',
                            'ons'     => 'ONS (Overnight)',
                            'sds'     => 'SDS (Same Day)',
                            'is'      => 'Instant',
                        ])
                        ->required()
                        ->default('regular'),
                    Forms\Components\TextInput::make('package_content')
                        ->label('Isi Paket')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Checkbox::make('is_fragile')
                        ->label('Barang Mudah Pecah (Fragile)')
                        ->columnSpanFull(),
                ])->columns(4),

            Forms\Components\Section::make('Finansial & Status')
                ->schema([
                    Forms\Components\TextInput::make('base_price')
                        ->label('Harga Dasar Ongkir')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly()
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::calculateTotal($set, $get)),
                    Forms\Components\TextInput::make('packing_fee')
                        ->label('Biaya Packing')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::calculateTotal($set, $get)),
                    Forms\Components\TextInput::make('insurance_fee')
                        ->label('Asuransi')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::calculateTotal($set, $get)),
                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Diskon')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($set, $get) => self::calculateTotal($set, $get)),
                    Forms\Components\TextInput::make('price')
                        ->label('Total Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly()
                        ->default(0),
                    Forms\Components\Select::make('current_status')
                        ->label('Status')
                        ->options([
                            'pending'     => 'Pending',
                            'picked_up'   => 'Picked Up',
                            'in_transit'  => 'Dalam Perjalanan',
                            'delivered'   => 'Terkirim',
                            'cancelled'   => 'Dibatalkan',
                        ])
                        ->required()
                        ->default('pending'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('No. AWB')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destinationDistrict.name')
                    ->label('Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight_kg')
                    ->label('Berat')
                    ->suffix(' Kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Ongkir')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'gray',
                        'picked_up'  => 'info',
                        'in_transit' => 'warning',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'    => 'Pending',
                        'picked_up'  => 'Dijemput',
                        'in_transit' => 'Dalam Perjalanan',
                        'delivered'  => 'Terkirim',
                        'cancelled'  => 'Dibatalkan',
                        default      => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl. Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('current_status')
                    ->label('Status')
                    ->options([
                        'pending'    => 'Pending',
                        'picked_up'  => 'Dijemput',
                        'in_transit' => 'Dalam Perjalanan',
                        'delivered'  => 'Terkirim',
                        'cancelled'  => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('package_type')
                    ->label('Jenis Layanan')
                    ->options([
                        'regular' => 'Reguler',
                        'ons'     => 'ONS',
                        'sds'     => 'SDS',
                        'is'      => 'Instant',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Shipment $record): string => route('print.resi.new', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('printBulk')
                        ->label('Cetak Terpilih')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('print.resi.bulk.new', ['ids' => $ids]);
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListResi::route('/'),
            'create' => Pages\CreateResi::route('/create'),
            'edit'   => Pages\EditResi::route('/{record}/edit'),
        ];
    }

    public static function updatePrice(callable $set, callable $get): void
    {
        $originId = $get('origin_district_id');
        $destId   = $get('destination_district_id');
        $weight   = (float) $get('weight_kg');
        $dimension = $get('dimension');

        if (! $originId || ! $destId || $weight <= 0) {
            return;
        }

        $p = $l = $t = 0.0;
        if ($dimension && preg_match('/(\d+)\s*[xX]\s*(\d+)\s*[xX]\s*(\d+)/', $dimension, $matches)) {
            $p = (float) $matches[1];
            $l = (float) $matches[2];
            $t = (float) $matches[3];
        }

        $rates = app(\App\Services\PricingService::class)->calculate(
            (string) $originId,
            (string) $destId,
            $weight,
            $p > 0 ? $p : null,
            $l > 0 ? $l : null,
            $t > 0 ? $t : null,
        );

        if ($rates->isNotEmpty()) {
            $set('base_price', $rates->first()['total_fee']);
            self::calculateTotal($set, $get);
        }
    }

    public static function calculateTotal(callable $set, callable $get): void
    {
        $basePrice = (float) $get('base_price');
        $packingFee = (float) $get('packing_fee');
        $insuranceFee = (float) $get('insurance_fee');
        $discountAmount = (float) $get('discount_amount');
        
        $total = ($basePrice + $packingFee + $insuranceFee) - $discountAmount;
        $set('price', max(0, $total));
    }
}
