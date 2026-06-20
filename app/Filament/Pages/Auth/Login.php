<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    /**
     * View path for the custom login page.
     */
    protected static string $view = 'filament.pages.auth.login';

    /**
     * Use the base layout to allow for full custom UI control.
     */
    protected static string $layout = 'filament-panels::layout.base';

    /**
     * Ensure the form data is correctly structured for Filament's Login logic.
     */
    public function mount(): void
    {
        parent::mount();
        
        // Custom default values if needed
        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'admin@cbn-logistics.co.id',
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }
}
