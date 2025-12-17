<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $today = now()->toDateString();
        
        return [
            Stat::make('Total Bookings', Booking::count())
                ->description('All time bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            Stat::make('Active Bookings', Booking::whereIn('status', ['reserved', 'checked_in'])->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Today\'s Check-ins', Booking::where('check_in_date', $today)->where('status', '!=', 'cancelled')->count())
                ->description('Arrivals today')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color('info'),
            Stat::make('Today\'s Check-outs', Booking::where('check_out_date', $today)->where('status', '!=', 'cancelled')->count())
                ->description('Departures today')
                ->descriptionIcon('heroicon-m-arrow-left-circle')
                ->color('warning'),
        ];
    }
}
