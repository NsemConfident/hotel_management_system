<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'id_number' => 'ID123456',
                'address' => '123 Main Street',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1987654321',
                'id_number' => 'ID654321',
                'address' => '456 Oak Avenue',
            ],
        ];

        foreach ($guests as $guest) {
            Guest::firstOrCreate(
                ['email' => $guest['email']],
                $guest,
            );
        }
    }
}
