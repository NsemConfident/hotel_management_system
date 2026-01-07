<?php

namespace App\Filament\Widgets\Receptionist;

use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomAvailabilityWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected function getStats(): array
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        return [
            Stat::make('Available Rooms', $availableRooms)
                ->description('Ready for guests')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Occupied Rooms', $occupiedRooms)
                ->description('Currently in use')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Maintenance', $maintenanceRooms)
                ->description('Under repair')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('danger'),
            Stat::make('Total Rooms', $totalRooms)
                ->description('All rooms in system')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('info'),
        ];
    }
}

