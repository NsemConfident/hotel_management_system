<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Single Room',
                'base_price' => 50_00 / 100,
                'max_occupancy' => 1,
                'description' => 'Cozy room for one guest with a single bed.',
            ],
            [
                'name' => 'Double Room',
                'base_price' => 80_00 / 100,
                'max_occupancy' => 2,
                'description' => 'Comfortable room for two guests with a double bed.',
            ],
            [
                'name' => 'Suite',
                'base_price' => 150_00 / 100,
                'max_occupancy' => 3,
                'description' => 'Spacious suite with living area and premium amenities.',
            ],
        ];

        foreach ($types as $type) {
            RoomType::firstOrCreate(
                ['name' => $type['name']],
                $type,
            );
        }
    }
}
