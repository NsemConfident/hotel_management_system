<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingTrendsChart extends ChartWidget
{
    protected ?string $heading = 'Booking Trends (Last 30 Days)';
    protected static ?int $sort = 5;

    public function getColumns(): int | array
    {
        return 2;
    }

    protected function getData(): array
    {
        $days = 30;
        $labels = [];
        $bookings = [];
        $checkIns = [];
        $checkOuts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('M d');
            $labels[] = $dateStr;

            // Total bookings created on this date
            $bookings[] = Booking::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->count();

            // Check-ins on this date
            $checkIns[] = Booking::whereDate('check_in_date', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->count();

            // Check-outs on this date
            $checkOuts[] = Booking::whereDate('check_out_date', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Bookings',
                    'data' => $bookings,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Check-ins',
                    'data' => $checkIns,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
                [
                    'label' => 'Check-outs',
                    'data' => $checkOuts,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor' => 'rgb(245, 158, 11)',
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
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
