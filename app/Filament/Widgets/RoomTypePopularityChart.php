<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\RoomType;
use Filament\Widgets\ChartWidget;

class RoomTypePopularityChart extends ChartWidget
{
    protected ?string $heading = 'Bookings by Room Type';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $roomTypes = RoomType::withCount(['rooms' => function ($query) {
            $query->has('bookings');
        }])->get();

        $labels = [];
        $bookings = [];
        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Blue
            'rgba(16, 185, 129, 0.8)',   // Green
            'rgba(245, 158, 11, 0.8)',   // Yellow
            'rgba(239, 68, 68, 0.8)',    // Red
            'rgba(139, 92, 246, 0.8)',   // Purple
            'rgba(236, 72, 153, 0.8)',   // Pink
        ];

        $backgroundColors = [];
        $borderColors = [];

        foreach ($roomTypes as $index => $roomType) {
            $labels[] = $roomType->name;
            
            // Count bookings for this room type
            $bookingCount = Booking::whereHas('room', function ($query) use ($roomType) {
                $query->where('room_type_id', $roomType->id);
            })
            ->where('status', '!=', 'cancelled')
            ->count();
            
            $bookings[] = $bookingCount;
            
            $colorIndex = $index % count($colors);
            $backgroundColors[] = $colors[$colorIndex];
            $borderColors[] = str_replace('0.8', '1', $colors[$colorIndex]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $bookings,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
