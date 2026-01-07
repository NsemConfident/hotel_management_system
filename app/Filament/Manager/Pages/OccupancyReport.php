<?php

namespace App\Filament\Manager\Pages;

use App\Models\Booking;
use App\Models\Room;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class OccupancyReport extends Page
{
    protected string $view = 'filament.manager.pages.occupancy-report';
    
    protected static ?string $title = 'Occupancy Reports';

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home-modern';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationLabel(): string
    {
        return 'Occupancy Report';
    }

    public $startDate;
    public $endDate;
    public $reportData = [];

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->generateReport();
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate'])) {
            $this->generateReport();
        }
    }

    public function generateReport(): void
    {
        $this->reportData = [
            'overall' => $this->getOverallOccupancy(),
            'byRoomType' => $this->getOccupancyByRoomType(),
            'dailyOccupancy' => $this->getDailyOccupancy(),
            'roomUtilization' => $this->getRoomUtilization(),
        ];
    }

    protected function getOverallOccupancy(): array
    {
        $totalRooms = Room::count();
        $dateRange = $this->getDateRange();
        $totalRoomDays = $totalRooms * count($dateRange);
        
        $occupiedDays = 0;
        foreach ($dateRange as $date) {
            $occupied = Booking::where('check_in_date', '<=', $date)
                ->where('check_out_date', '>', $date)
                ->where('status', '!=', 'cancelled')
                ->distinct('room_id')
                ->count('room_id');
            $occupiedDays += $occupied;
        }
        
        $occupancyRate = $totalRoomDays > 0 ? ($occupiedDays / $totalRoomDays) * 100 : 0;
        
        return [
            'total_rooms' => $totalRooms,
            'total_room_days' => $totalRoomDays,
            'occupied_days' => $occupiedDays,
            'occupancy_rate' => $occupancyRate,
        ];
    }

    protected function getOccupancyByRoomType(): array
    {
        $dateRange = $this->getDateRange();
        
        $roomTypes = DB::table('room_types')
            ->join('rooms', 'room_types.id', '=', 'rooms.room_type_id')
            ->select('room_types.id', 'room_types.name', DB::raw('COUNT(rooms.id) as room_count'))
            ->groupBy('room_types.id', 'room_types.name')
            ->get();
        
        $results = [];
        foreach ($roomTypes as $roomType) {
            $totalRoomDays = $roomType->room_count * count($dateRange);
            $occupiedDays = 0;
            
            foreach ($dateRange as $date) {
                $occupied = Booking::whereHas('room', function ($query) use ($roomType) {
                        $query->where('room_type_id', $roomType->id);
                    })
                    ->where('check_in_date', '<=', $date)
                    ->where('check_out_date', '>', $date)
                    ->where('status', '!=', 'cancelled')
                    ->distinct('room_id')
                    ->count('room_id');
                $occupiedDays += $occupied;
            }
            
            $occupancyRate = $totalRoomDays > 0 ? ($occupiedDays / $totalRoomDays) * 100 : 0;
            
            $results[] = [
                'room_type' => $roomType->name,
                'room_count' => $roomType->room_count,
                'total_room_days' => $totalRoomDays,
                'occupied_days' => $occupiedDays,
                'occupancy_rate' => $occupancyRate,
            ];
        }
        
        return $results;
    }

    protected function getDailyOccupancy(): array
    {
        $dateRange = $this->getDateRange();
        $totalRooms = Room::count();
        
        $results = [];
        foreach ($dateRange as $date) {
            $occupied = Booking::where('check_in_date', '<=', $date)
                ->where('check_out_date', '>', $date)
                ->where('status', '!=', 'cancelled')
                ->distinct('room_id')
                ->count('room_id');
            
            $occupancyRate = $totalRooms > 0 ? ($occupied / $totalRooms) * 100 : 0;
            
            $results[] = [
                'date' => $date,
                'occupied' => $occupied,
                'available' => $totalRooms - $occupied,
                'occupancy_rate' => $occupancyRate,
            ];
        }
        
        return $results;
    }

    protected function getRoomUtilization(): array
    {
        return Room::withCount(['bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled')
                    ->whereBetween('check_in_date', [$this->startDate, $this->endDate]);
            }])
            ->with('roomType')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($room) {
                return [
                    'room_number' => $room->room_number,
                    'room_type' => $room->roomType->name,
                    'bookings' => $room->bookings_count,
                ];
            })
            ->toArray();
    }

    protected function getDateRange(): array
    {
        $start = \Carbon\Carbon::parse($this->startDate);
        $end = \Carbon\Carbon::parse($this->endDate);
        $dates = [];
        
        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }
        
        return $dates;
    }
}

