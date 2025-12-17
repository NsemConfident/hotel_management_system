<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Room;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OccupancyChart extends ChartWidget
{
    protected ?string $heading = 'Occupancy Rate (Last 30 Days)';
    protected static ?int $sort = 7;

    public function getColumns(): int | array
    {
        return 2;
    }

    protected function getData(): array
    {
        $days = 30;
        $labels = [];
        $occupancyRates = [];
        $totalRooms = Room::count();

        if ($totalRooms === 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'Occupancy Rate (%)',
                        'data' => [],
                        'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                        'borderColor' => 'rgb(59, 130, 246)',
                    ],
                ],
                'labels' => [],
            ];
        }

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('M d');
            $labels[] = $dateStr;

            // Count occupied rooms on this date
            $occupiedCount = Booking::where('check_in_date', '<=', $date->toDateString())
                ->where('check_out_date', '>=', $date->toDateString())
                ->whereIn('status', ['reserved', 'checked_in'])
                ->distinct('room_id')
                ->count('room_id');

            $occupancyRate = round(($occupiedCount / $totalRooms) * 100, 1);
            $occupancyRates[] = $occupancyRate;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Occupancy Rate (%)',
                    'data' => $occupancyRates,
                    'backgroundColor' => 'rgba(139, 92, 246, 0.5)',
                    'borderColor' => 'rgb(139, 92, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
            ],
        ];
    }
}
