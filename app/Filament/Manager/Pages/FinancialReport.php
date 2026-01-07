<?php

namespace App\Filament\Manager\Pages;

use App\Models\Booking;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class FinancialReport extends Page
{
    protected string $view = 'filament.manager.pages.financial-report';
    
    protected static ?string $title = 'Financial Reports';

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chart-bar-square';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationLabel(): string
    {
        return 'Financial Report';
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
            'summary' => $this->getSummary(),
            'byRoomType' => $this->getRevenueByRoomType(),
            'dailyRevenue' => $this->getDailyRevenue(),
            'paymentMethods' => $this->getPaymentMethods(),
        ];
    }

    protected function getSummary(): array
    {
        $bookings = Booking::whereBetween('check_in_date', [$this->startDate, $this->endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalRevenue = $bookings->sum('total_amount');
        $totalPaid = $bookings->sum('amount_paid');
        $totalBookings = $bookings->count();
        $averageBooking = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_paid' => $totalPaid,
            'outstanding' => $totalRevenue - $totalPaid,
            'total_bookings' => $totalBookings,
            'average_booking' => $averageBooking,
        ];
    }

    protected function getRevenueByRoomType(): array
    {
        return Booking::whereBetween('check_in_date', [$this->startDate, $this->endDate])
            ->where('status', '!=', 'cancelled')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'room_types.name as room_type',
                DB::raw('SUM(bookings.total_amount) as revenue'),
                DB::raw('COUNT(bookings.id) as bookings_count')
            )
            ->groupBy('room_types.id', 'room_types.name')
            ->orderBy('revenue', 'desc')
            ->get()
            ->toArray();
    }

    protected function getDailyRevenue(): array
    {
        return Booking::whereBetween('check_in_date', [$this->startDate, $this->endDate])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(check_in_date) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();
    }

    protected function getPaymentMethods(): array
    {
        return DB::table('booking_payments')
            ->join('bookings', 'booking_payments.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.check_in_date', [$this->startDate, $this->endDate])
            ->where('bookings.status', '!=', 'cancelled')
            ->select(
                'booking_payments.method',
                DB::raw('SUM(booking_payments.amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('booking_payments.method')
            ->get()
            ->toArray();
    }
}

