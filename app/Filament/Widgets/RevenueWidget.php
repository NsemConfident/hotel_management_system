<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RevenueWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth();
        $thisYear = now()->startOfYear();

        $todayRevenue = Booking::where('check_in_date', '<=', $today)
            ->where('check_out_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthRevenue = Booking::where('check_in_date', '>=', $thisMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $yearRevenue = Booking::where('check_in_date', '>=', $thisYear)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $totalPaid = Booking::where('status', '!=', 'cancelled')
            ->sum('amount_paid');

        return [
            Stat::make('Today\'s Revenue', '$' . number_format($todayRevenue, 2))
                ->description('From current guests')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('This Month', '$' . number_format($monthRevenue, 2))
                ->description('Monthly revenue')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
            Stat::make('This Year', '$' . number_format($yearRevenue, 2))
                ->description('Yearly revenue')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
            Stat::make('Total Collected', '$' . number_format($totalPaid, 2))
                ->description('All payments received')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
