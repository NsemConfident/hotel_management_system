<?php

namespace App\Filament\Widgets\Manager;

use App\Models\Guest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuestLoyaltyWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected function getStats(): array
    {
        $vipGuests = Guest::where('is_vip', true)->count();
        $totalGuests = Guest::count();
        $vipPercentage = $totalGuests > 0 ? ($vipGuests / $totalGuests) * 100 : 0;

        // Top loyalty tier guests
        $topTierGuests = Guest::where('loyalty_points', '>=', 10000)->count();
        
        // Average loyalty points
        $avgLoyaltyPoints = Guest::avg('loyalty_points') ?? 0;
        
        // Total loyalty points distributed
        $totalLoyaltyPoints = Guest::sum('loyalty_points') ?? 0;

        return [
            Stat::make('VIP Guests', $vipGuests)
                ->description(number_format($vipPercentage, 1) . '% of total guests')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            Stat::make('Top Tier Members', $topTierGuests)
                ->description('10,000+ loyalty points')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
            Stat::make('Avg Loyalty Points', number_format($avgLoyaltyPoints, 0))
                ->description('Per guest average')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),
            Stat::make('Total Points', number_format($totalLoyaltyPoints, 0))
                ->description('Loyalty points in circulation')
                ->descriptionIcon('heroicon-m-gift')
                ->color('primary'),
        ];
    }
}

