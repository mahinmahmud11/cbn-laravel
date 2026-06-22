<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ArtisanConsolePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Artisan Console';
    protected static ?string $title           = 'Web Artisan Console';
    protected static ?string $navigationGroup = 'Sistem & Keamanan';
    protected static ?int    $navigationSort  = 95;
    protected static string  $view            = 'filament.pages.artisan-console-page';

    // Whitelist statik — satu-satunya sumber kebenaran, tidak boleh berasal dari input user
    private const COMMANDS = [
        'cache:clear'               => ['cmd' => 'cache:clear',            'args' => []],
        'config:clear'              => ['cmd' => 'config:clear',           'args' => []],
        'view:clear'                => ['cmd' => 'view:clear',             'args' => []],
        'route:clear'               => ['cmd' => 'route:clear',            'args' => []],
        'queue:restart'             => ['cmd' => 'queue:restart',          'args' => []],
        'migrate'                   => ['cmd' => 'migrate',                'args' => ['--force' => true]],
        'migrate:smart'             => ['cmd' => 'migrate:smart',          'args' => []],
        'migrate:status'            => ['cmd' => 'migrate:status',         'args' => []],
        'storage:link'              => ['cmd' => 'storage:link',           'args' => ['--force' => true]],
        'activitylog:clean --days=90' => ['cmd' => 'activitylog:clean',   'args' => ['--days' => 90]],
    ];

    public ?array  $data   = [];
    public ?string $output = null;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('command')
                    ->label('Pilih Command Artisan')
                    ->options(array_combine(array_keys(self::COMMANDS), array_keys(self::COMMANDS)))
                    ->placeholder('-- Pilih command --')
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('execute')
                ->label('Jalankan Command')
                ->color('warning')
                ->icon('heroicon-o-play')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Eksekusi')
                ->modalDescription(fn (): string => 'Anda yakin ingin menjalankan: ' . ($this->data['command'] ?? '(belum dipilih)') . '?')
                ->modalSubmitActionLabel('Ya, Jalankan')
                ->action('runCommand'),
        ];
    }

    public function runCommand(): void
    {
        $selected = $this->data['command'] ?? null;

        // Validasi: hanya boleh key yang ada di COMMANDS — tidak ada celah injection
        if (!$selected || !array_key_exists($selected, self::COMMANDS)) {
            $this->output = '[ERROR] Command tidak valid atau tidak ada dalam whitelist.';
            return;
        }

        $entry = self::COMMANDS[$selected];

        try {
            Artisan::call($entry['cmd'], $entry['args']);
            $raw = Artisan::output();
            $this->output = $raw ?: "✓ Command '{$entry['cmd']}' selesai dijalankan (tidak ada output teks).";

            // Audit log — wajib: setiap eksekusi tercatat
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['command' => $selected])
                ->log('Menjalankan Artisan Console: ' . $selected);

            Notification::make()
                ->title('Command berhasil dijalankan')
                ->success()
                ->send();

        } catch (\Throwable $e) {
            $this->output = '[EXCEPTION] ' . $e->getMessage();

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['command' => $selected, 'error' => $e->getMessage()])
                ->log('Artisan Console GAGAL: ' . $selected);

            Notification::make()
                ->title('Command gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
