<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => Role::ADMIN,
                'description' => 'Full system access. Can manage all resources, users, and settings.',
            ],
            [
                'name' => 'Manager',
                'slug' => Role::MANAGER,
                'description' => 'Can manage bookings, rooms, guests, and room types. Cannot delete critical data or manage users.',
            ],
            [
                'name' => 'Receptionist',
                'slug' => Role::RECEPTIONIST,
                'description' => 'Can view and manage bookings, guests, and check-in/out guests. Limited access to settings.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
