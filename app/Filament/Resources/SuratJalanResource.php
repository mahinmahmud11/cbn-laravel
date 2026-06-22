<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SuratJalanResource\Pages;
use App\Filament\Resources\SuratJalanResource\RelationManagers;
use App\Models\Armada;
use App\Models\SuratJalan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuratJalanResource extends Resource
{
    protected static ?string $model = SuratJalan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Surat Jalan';

    protected static ?string $modelLabel = 'Surat Jalan';

    protected static ?string $pluralModelLabel = 'Surat Jalan';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    // ─── Form ────────────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Surat Jalan')
                ->schema([
                    Forms\Components\TextInput::make('nomor')
                        ->label('Nomor Surat Jalan')
                        ->disabled()
                        ->dehydrated()
                        ->placeholder('Dibuat otomatis saat simpan')
                        ->helperText('Format: SJ-YYMM-NNNNN'),

                    Forms\Components\DatePicker::make('tanggal_kirim')
                        ->label('Tanggal Kirim')
                        ->required()
                        ->default(now()),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'draft'   => 'Draft',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                            'gagal'   => 'Gagal',
                        ])
                        ->default('draft'),
                ])->columns(3),

            Forms\Components\Section::make('Data Driver & Kendaraan')
                ->schema([
                    Forms\Components\Select::make('armada_id')
                        ->label('Kendaraan (Armada)')
                        ->relationship('armada', 'nopol')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $armada = Armada::find($state);
                                if ($armada) {
                                    // Auto-fill driver dari armada jika ada keterangan
                                    // (data driver tidak disimpan di armada, hanya nopol — biarkan kosong)
                                }
                            }
                        })
                        ->helperText('Pilih kendaraan dari master armada, atau isi manual di bawah'),

                    Forms\Components\TextInput::make('driver_name')
                        ->label('Nama Driver')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('driver_phone')
                        ->label('No. HP Driver')
                        ->tel()
                        ->maxLength(20),
                ])->columns(3),

            Forms\Components\Section::make('Catatan')
                ->schema([
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // ─── Table ───────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->label('No. Surat Jalan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('tanggal_kirim')
                    ->label('Tgl Kirim')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('armada.nopol')
                    ->label('Kendaraan')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('driver_name')
                    ->label('Driver')
                    ->searchable(),

                Tables\Columns\TextColumn::make('driver_phone')
                    ->label('HP Driver')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('shipments_count')
                    ->label('Jumlah Resi')
                    ->counts('shipments')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'   => 'gray',
                        'dikirim' => 'warning',
                        'selesai' => 'success',
                        'gagal'   => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft'   => 'Draft',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'gagal'   => 'Gagal',
                    ]),

                Tables\Filters\Filter::make('tanggal_kirim')
                    ->form([
                        Forms\Components\DatePicker::make('dari')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q, $date) => $q->whereDate('tanggal_kirim', '>=', $date))
                            ->when($data['sampai'], fn ($q, $date) => $q->whereDate('tanggal_kirim', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShipmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuratJalan::route('/'),
            'create' => Pages\CreateSuratJalan::route('/create'),
            'edit'   => Pages\EditSuratJalan::route('/{record}/edit'),
        ];
    }
}
