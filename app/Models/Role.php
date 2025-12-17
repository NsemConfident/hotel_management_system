<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Role constants
    public const ADMIN = 'admin';
    public const RECEPTIONIST = 'receptionist';
    public const MANAGER = 'manager';

    // Check if user has specific role
    public function isAdmin(): bool
    {
        return $this->slug === self::ADMIN;
    }

    public function isReceptionist(): bool
    {
        return $this->slug === self::RECEPTIONIST;
    }

    public function isManager(): bool
    {
        return $this->slug === self::MANAGER;
    }
}
