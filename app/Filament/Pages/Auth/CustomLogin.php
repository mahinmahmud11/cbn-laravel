<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.custom-login';

    /**
     * Use the explicit base layout component path to resolve the view error.
     */
    protected static string $layout = 'filament-panels::components.layout.base';

    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'admin@cbn-logistics.co.id',
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }

    public function getLayout(): string
    {
        return static::$layout;
    }
}
