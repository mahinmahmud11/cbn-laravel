<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Company;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model            = Invoice::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationGroup  = 'Keuangan & Komisi';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $navigationLabel  = 'Penagihan (Invoicing)';
    protected static ?string $modelLabel       = 'Invoice';
    protected static ?string $pluralModelLabel = 'Daftar Invoice';

    public static function getModelLabel(): string       { return 'Invoice'; }
    public static function getPluralModelLabel(): string { return 'Daftar Invoice'; }
    public static function getNavigationLabel(): string  { return 'Penagihan (Invoicing)'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Penagihan')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->label('Pelanggan Korporat')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set): void {
                            if ($company = Company::find($state)) {
                                $set('due_date', now()->addDays($company->payment_term_days)->toDateString());
                                $set('customer_name', $company->name);
                                $set('customer_address', $company->billing_address);
                            }
                        })
                        ->helperText('Kosongkan jika invoice bukan untuk pelanggan korporat.'),
                    Forms\Components\Select::make('billing_status')
                        ->label('Status Penagihan')
                        ->options([
                            'belum_ditagih'    => 'Belum Ditagih',
                            'sudah_ditagih'    => 'Sudah Ditagih',
                            'sebagian_dibayar' => 'Sebagian Dibayar',
                            'lunas'            => 'Lunas',
                            'dibatalkan'       => 'Dibatalkan',
                        ])
                        ->default('belum_ditagih')
                        ->required()
                        ->live(),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('Jatuh Tempo')
                        ->nullable()
                        ->helperText('Otomatis terisi dari termin pembayaran perusahaan.'),
                    Forms\Components\TextInput::make('paid_amount')
                        ->label('Jumlah Dibayar (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->minValue(0)
                        ->visible(fn (callable $get): bool =>
                            in_array($get('billing_status'), ['sebagian_dibayar', 'lunas'])
                        ),
                ]),
            Forms\Components\Section::make('Informasi Invoice')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('Nomor Invoice')
                        ->default(fn (): string => 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))))
                        ->readOnly()
                        ->required(),
                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total Tagihan (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->readOnly()
                        ->helperText('Otomatis dihitung dari jumlah resi yang ditambahkan.'),
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Nama Pelanggan')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Diisi otomatis saat memilih Pelanggan Korporat.'),
                    Forms\Components\Textarea::make('customer_address')
                        ->label('Alamat Penagihan')
                        ->helperText('Diisi otomatis saat memilih Pelanggan Korporat.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Internal')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('due_date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('billing_status')
                    ->label('Status Penagihan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'belum_ditagih'    => 'gray',
                        'sudah_ditagih'    => 'warning',
                        'sebagian_dibayar' => 'info',
                        'lunas'            => 'success',
                        'dibatalkan'       => 'danger',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'belum_ditagih'    => 'Belum Ditagih',
                        'sudah_ditagih'    => 'Sudah Ditagih',
                        'sebagian_dibayar' => 'Sebagian Dibayar',
                        'lunas'            => 'Lunas',
                        'dibatalkan'       => 'Dibatalkan',
                        default            => '-',
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn (Invoice $record): string =>
                        $record->due_date
                        && $record->due_date->isPast()
                        && ! in_array($record->billing_status, ['lunas', 'dibatalkan'])
                            ? 'danger'
                            : 'gray'
                    ),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Sudah Dibayar')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('billing_status')
                    ->label('Status Penagihan')
                    ->options([
                        'belum_ditagih'    => 'Belum Ditagih',
                        'sudah_ditagih'    => 'Sudah Ditagih',
                        'sebagian_dibayar' => 'Sebagian Dibayar',
                        'lunas'            => 'Lunas',
                        'dibatalkan'       => 'Dibatalkan',
                    ]),
                Tables\Filters\Filter::make('overdue')
                    ->label('Sudah Jatuh Tempo')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereDate('due_date', '<', now())
                              ->whereNotIn('billing_status', ['lunas', 'dibatalkan'])
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Invoice $record): string => route('print.invoice', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
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
            RelationManagers\ShipmentsRelationManager::class,
            RelationManagers\ShipItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view'   => Pages\ViewInvoice::route('/{record}'),
            'edit'   => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
