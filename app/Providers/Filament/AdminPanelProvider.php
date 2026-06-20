<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\NavigationItem;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->colors([
            'primary' => '#FF750F',
                'gray' => Color::Slate,
            ])
            ->font('Plus Jakarta Sans')
            ->brandName('CBN Logistics ERP')
            ->brandLogo(fn () => view('filament.components.brand-logo'))
            ->favicon(asset('images/logo.png'))
            ->sidebarCollapsibleOnDesktop()
            ->breadcrumbs(true)
            ->navigationItems([
                NavigationItem::make('Kembali ke Beranda')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->sort(-1),
            ])
            ->renderHook(
                'panels::head.end',
                fn (): string => Blade::render('<link rel="stylesheet" href="{{ asset(\'css/filament/admin/theme.css\') }}">'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\MonthlyRevenue::class,
                \App\Filament\Widgets\ShipmentTrends::class,
                \App\Filament\Widgets\RecentShipments::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
