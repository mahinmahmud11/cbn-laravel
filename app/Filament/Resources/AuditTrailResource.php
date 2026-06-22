<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AuditTrailResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Model;

class AuditTrailResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $slug = 'audit-trails';

    protected static ?string $navigationLabel = 'Audit Trail';
    protected static ?string $modelLabel = 'Audit Trail';
    protected static ?string $pluralModelLabel = 'Audit Trails';
    protected static ?string $navigationGroup = 'Sistem & Keamanan';
    protected static ?int $navigationSort = 90;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'administrator']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Dilakukan Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modul')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'info',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('properties')
                    ->label('Detail Perubahan')
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';

                        // Spatie Activity auto-cast properties ke Collection — paksa ke array
                        $decoded = is_array($state)
                            ? $state
                            : (is_object($state) && method_exists($state, 'toArray')
                                ? $state->toArray()
                                : json_decode((string) $state, true));

                        if (!is_array($decoded) || empty($decoded)) {
                            return '-';
                        }

                        $parts = [];

                        if (isset($decoded['old']) && is_array($decoded['old'])) {
                            $parts[] = '<div class="text-danger-500 dark:text-danger-400 font-mono text-xs">'
                                . '<span class="font-bold">— Old:</span><br/>'
                                . nl2br(htmlspecialchars(json_encode($decoded['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)))
                                . '</div>';
                        }

                        if (isset($decoded['attributes']) && is_array($decoded['attributes'])) {
                            $parts[] = '<div class="text-success-600 dark:text-success-400 font-mono text-xs mt-1">'
                                . '<span class="font-bold">+ New:</span><br/>'
                                . nl2br(htmlspecialchars(json_encode($decoded['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)))
                                . '</div>';
                        }

                        if (empty($parts)) {
                            $parts[] = '<div class="font-mono text-xs">'
                                . nl2br(htmlspecialchars(json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)))
                                . '</div>';
                        }

                        return \Illuminate\Support\HtmlString::make(
                            '<div class="space-y-1 max-w-sm">' . implode('', $parts) . '</div>'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Aksi')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Modul')
                    ->options(function () {
                        return Activity::query()
                            ->select('subject_type')
                            ->distinct()
                            ->pluck('subject_type', 'subject_type')
                            ->mapWithKeys(fn ($type) => [$type => class_basename($type)])
                            ->toArray();
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditTrails::route('/'),
        ];
    }
}
