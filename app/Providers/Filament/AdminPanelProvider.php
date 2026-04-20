<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Settings;
use App\Filament\Resources\AlatResource;
use App\Filament\Resources\BahanCairanLamaResourceResource;
use App\Filament\Resources\PeminjamanResourceResource;
use App\Filament\Resources\BahanPadatResourceResourceResource;
use App\Models\Alat;
use App\Models\User;
use App\Filament\Resources\UserResource;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin1')
            ->path('admin')
            ->login()
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/favicon.ico'))
            // ->brandLogo(asset('images/UIn2.png'))
            //  ->brandLogoHeight('10rem')
            ->brandName('Prodi Kimia')
            ->sidebarFullyCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Lime,
            ])
            //  ->topNavigation()
            ->font('Nunito')
            ->darkmode(true) //Matikan Darkmode
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            // ->widgets([
            // Widgets\AccountWidget::class,
            // Widgets\FilamentInfoWidget::class,
            // ])
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
            ->authMiddleware([
                Authenticate::class,
            ])->userMenuItems([
                MenuItem::make()
                    ->label('User Settings')
                    ->url(fn(): string => UserResource::getUrl())
                    // ->url(url: '/AlatResource')
                    ->icon('heroicon-o-cog-6-tooth'),
                // ...
            ]);
    }
}
