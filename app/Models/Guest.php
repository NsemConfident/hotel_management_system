<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
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

    protected $hidden = [
        'password',
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

    /**
     * Set the password attribute (hash it automatically)
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Verify password
     */
    public function verifyPassword($password): bool
    {
        if (!$this->password) {
            return false;
        }
        return Hash::check($password, $this->password);
    }
}
