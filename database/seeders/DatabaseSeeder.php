<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed a default user for login.
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password', // consider hashing in production
                'email_verified_at' => now(),
            ],
        );

        // Domain data
        $this->call([
            RoomTypeSeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
