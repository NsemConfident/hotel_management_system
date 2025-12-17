<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'id_number',
        'address',
        'is_vip',
        'loyalty_points',
        'date_of_birth',
        'nationality',
        'preferred_language',
        'preferences',
        'special_requests',
        'notes',
        'last_visit_at',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'loyalty_points' => 'integer',
        'date_of_birth' => 'date',
        'preferences' => 'array',
        'last_visit_at' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get total amount spent by guest
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') ?? 0;
    }

    /**
     * Get total nights stayed
     */
    public function getTotalNightsAttribute(): int
    {
        return $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->get()
            ->sum(function ($booking) {
                return \Carbon\Carbon::parse($booking->check_in_date)
                    ->diffInDays(\Carbon\Carbon::parse($booking->check_out_date));
            });
    }

    /**
     * Get loyalty tier based on points
     */
    public function getLoyaltyTierAttribute(): string
    {
        if ($this->loyalty_points >= 10000) {
            return 'Platinum';
        } elseif ($this->loyalty_points >= 5000) {
            return 'Gold';
        } elseif ($this->loyalty_points >= 1000) {
            return 'Silver';
        }
        return 'Bronze';
    }

    /**
     * Add loyalty points
     */
    public function addLoyaltyPoints(int $points, string $reason = ''): void
    {
        $this->increment('loyalty_points', $points);
    }

    /**
     * Calculate loyalty points from booking
     */
    public function calculateLoyaltyPointsFromBooking(Booking $booking): int
    {
        // 1 point per dollar spent
        return (int) $booking->total_amount;
    }
}
