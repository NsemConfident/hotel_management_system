<?php

namespace App\Filament\Manager\Pages;

use App\Models\Guest;
use Filament\Pages\Page;

class GuestReport extends Page
{
    protected string $view = 'filament.manager.pages.guest-report';
    
    protected static ?string $title = 'Guest Reports';

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationLabel(): string
    {
        return 'Guest Report';
    }

    public $reportData = [];

    public function mount(): void
    {
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $this->reportData = [
            'loyaltyDistribution' => $this->getLoyaltyDistribution(),
            'topGuests' => $this->getTopGuests(),
            'vipGuests' => $this->getVipGuests(),
            'newGuests' => $this->getNewGuests(),
        ];
    }

    protected function getLoyaltyDistribution(): array
    {
        return [
            'platinum' => Guest::where('loyalty_points', '>=', 10000)->count(),
            'gold' => Guest::whereBetween('loyalty_points', [5000, 9999])->count(),
            'silver' => Guest::whereBetween('loyalty_points', [1000, 4999])->count(),
            'bronze' => Guest::where('loyalty_points', '<', 1000)->count(),
        ];
    }

    protected function getTopGuests(int $limit = 10): array
    {
        return Guest::withCount(['bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->orderBy('loyalty_points', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($guest) {
                return [
                    'name' => $guest->full_name,
                    'email' => $guest->email,
                    'loyalty_points' => $guest->loyalty_points,
                    'tier' => $guest->loyalty_tier,
                    'bookings' => $guest->bookings_count,
                    'total_spent' => $guest->total_spent,
                    'nights' => $guest->total_nights,
                ];
            })
            ->toArray();
    }

    protected function getVipGuests(): array
    {
        return Guest::where('is_vip', true)
            ->orderBy('loyalty_points', 'desc')
            ->get()
            ->map(function ($guest) {
                return [
                    'name' => $guest->full_name,
                    'email' => $guest->email,
                    'loyalty_points' => $guest->loyalty_points,
                    'total_spent' => $guest->total_spent,
                ];
            })
            ->toArray();
    }

    protected function getNewGuests(int $limit = 10): array
    {
        return Guest::whereHas('bookings', function ($query) {
                $query->where('status', '!=', 'cancelled');
            }, '=', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($guest) {
                return [
                    'name' => $guest->full_name,
                    'email' => $guest->email,
                    'first_booking' => $guest->bookings()->first()?->check_in_date?->format('Y-m-d'),
                ];
            })
            ->toArray();
    }
}

