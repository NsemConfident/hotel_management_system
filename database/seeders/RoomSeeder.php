<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $single = RoomType::where('name', 'Single Room')->first();
        $double = RoomType::where('name', 'Double Room')->first();
        $suite = RoomType::where('name', 'Suite')->first();

        if (! $single || ! $double || ! $suite) {
            $this->call(RoomTypeSeeder::class);

            $single = RoomType::where('name', 'Single Room')->first();
            $double = RoomType::where('name', 'Double Room')->first();
            $suite = RoomType::where('name', 'Suite')->first();
        }

        $rooms = [
            ['room_type_id' => $single?->id, 'room_number' => '101', 'floor' => 1, 'status' => 'available'],
            ['room_type_id' => $single?->id, 'room_number' => '102', 'floor' => 1, 'status' => 'available'],
            ['room_type_id' => $double?->id, 'room_number' => '201', 'floor' => 2, 'status' => 'available'],
            ['room_type_id' => $double?->id, 'room_number' => '202', 'floor' => 2, 'status' => 'maintenance'],
            ['room_type_id' => $suite?->id, 'room_number' => '301', 'floor' => 3, 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            if ($room['room_type_id']) {
                Room::firstOrCreate(
                    ['room_number' => $room['room_number']],
                    $room,
                );
            }
        }
    }
}
