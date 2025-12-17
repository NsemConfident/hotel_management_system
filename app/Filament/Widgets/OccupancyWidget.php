<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OccupancyWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected function getStats(): array
    {
        $today = now()->toDateString();
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        // Calculate occupancy rate
        $occupiedBookings = Booking::where('check_in_date', '<=', $today)
            ->where('check_out_date', '>=', $today)
            ->whereIn('status', ['reserved', 'checked_in'])
            ->distinct('room_id')
            ->count('room_id');

        $occupancyRate = $totalRooms > 0 ? round(($occupiedBookings / $totalRooms) * 100, 1) : 0;

        return [
            Stat::make('Total Rooms', $totalRooms)
                ->description('All rooms in system')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary'),
            Stat::make('Available Rooms', $availableRooms)
                ->description('Ready for booking')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Occupied Rooms', $occupiedRooms)
                ->description('Currently occupied')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description('Current occupancy')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('info'),
        ];
    }
}
