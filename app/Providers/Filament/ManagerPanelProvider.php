<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Http\Middleware\EnsureUserIsManagerOrAdmin;
use App\Filament\Widgets\BookingStatsWidget;
use App\Filament\Widgets\BookingTrendsChart;
use App\Filament\Widgets\BookingsTableWidget;
use App\Filament\Widgets\OccupancyChart;
use App\Filament\Widgets\OccupancyWidget;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\RevenueWidget;
use App\Filament\Widgets\RoomTypePopularityChart;
use App\Filament\Widgets\Manager\GuestLoyaltyWidget;
use App\Filament\Widgets\Manager\PerformanceMetricsWidget;
use App\Filament\Widgets\Manager\UpcomingCheckoutsWidget;
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

class ManagerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('manager')
            ->path('manager')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandName('Hotel Manager Dashboard')
            ->brandLogo(asset('favicon.svg'))
            ->favicon(asset('favicon.svg'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverPages(in: app_path('Filament/Manager/Pages'), for: 'App\Filament\Manager\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->discoverWidgets(in: app_path('Filament/Widgets/Manager'), for: 'App\Filament\Widgets\Manager')
            ->widgets([
                AccountWidget::class,
                BookingStatsWidget::class,
                OccupancyWidget::class,
                RevenueWidget::class,
                PerformanceMetricsWidget::class,
                GuestLoyaltyWidget::class,
                BookingTrendsChart::class,
                RevenueChart::class,
                OccupancyChart::class,
                RoomTypePopularityChart::class,
                UpcomingCheckoutsWidget::class,
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
                EnsureUserIsManagerOrAdmin::class,
            ])
            ->authGuard('web')
            ->authPasswordBroker('users');
    }
}

