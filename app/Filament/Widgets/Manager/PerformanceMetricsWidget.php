<?php

namespace App\Filament\Widgets\Manager;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PerformanceMetricsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // Average Revenue Per Room (RevPAR)
        $totalRooms = Room::count();
        $totalRevenue = Booking::where('check_in_date', '>=', $thisMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $daysInMonth = now()->daysInMonth;
        $revPAR = $totalRooms > 0 ? ($totalRevenue / ($totalRooms * $daysInMonth)) : 0;

        // Average Daily Rate (ADR)
        $bookingsCount = Booking::where('check_in_date', '>=', $thisMonth)
            ->where('status', '!=', 'cancelled')
            ->count();
        $ADR = $bookingsCount > 0 ? ($totalRevenue / $bookingsCount) : 0;

        // Revenue Growth
        $lastMonthRevenue = Booking::whereBetween('check_in_date', [$lastMonth, $lastMonthEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // Guest Retention Rate
        $totalGuests = Guest::count();
        $repeatGuests = Guest::whereHas('bookings', function ($query) {
            $query->where('status', '!=', 'cancelled');
        }, '>=', 2)->count();
        $retentionRate = $totalGuests > 0 ? ($repeatGuests / $totalGuests) * 100 : 0;

        return [
            Stat::make('RevPAR', '$' . number_format($revPAR, 2))
                ->description('Revenue Per Available Room this month')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary'),
            Stat::make('ADR', '$' . number_format($ADR, 2))
                ->description('Average Daily Rate')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Revenue Growth', number_format($revenueGrowth, 1) . '%')
                ->description($revenueGrowth >= 0 ? 'Increase from last month' : 'Decrease from last month')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger'),
            Stat::make('Guest Retention', number_format($retentionRate, 1) . '%')
                ->description('Repeat guests rate')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}

