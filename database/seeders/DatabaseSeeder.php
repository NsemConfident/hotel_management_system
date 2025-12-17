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

        // Roles must be seeded first
        $this->call([
            RoleSeeder::class,
        ]);

        // Assign admin role to default user
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminRole = \App\Models\Role::where('slug', \App\Models\Role::ADMIN)->first();
            if ($adminRole) {
                $adminUser->update(['role_id' => $adminRole->id]);
            }
        }

        // Domain data
        $this->call([
            RoomTypeSeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
