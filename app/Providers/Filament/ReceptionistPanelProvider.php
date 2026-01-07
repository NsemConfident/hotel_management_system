<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Http\Middleware\EnsureUserIsReceptionistOrAdmin;
use App\Filament\Widgets\BookingStatsWidget;
use App\Filament\Widgets\BookingsTableWidget;
use App\Filament\Widgets\Receptionist\TodayCheckinsWidget;
use App\Filament\Widgets\Receptionist\TodayCheckoutsWidget;
use App\Filament\Widgets\Receptionist\RoomAvailabilityWidget;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ReceptionistPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('receptionist')
            ->path('receptionist')
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->brandName('Front Desk Dashboard')
            ->brandLogo(asset('favicon.svg'))
            ->favicon(asset('favicon.svg'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Receptionist'), for: 'App\Filament\Widgets\Receptionist')
            ->widgets([
                AccountWidget::class,
                BookingStatsWidget::class,
                TodayCheckinsWidget::class,
                TodayCheckoutsWidget::class,
                RoomAvailabilityWidget::class,
                BookingsTableWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
                EnsureUserIsReceptionistOrAdmin::class,
            ])
            ->authGuard('web')
            ->authPasswordBroker('users');
    }
}

