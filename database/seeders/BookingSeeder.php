<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Room::count() === 0) {
            $this->call([
                RoomTypeSeeder::class,
                RoomSeeder::class,
            ]);
        }

        if (Guest::count() === 0) {
            $this->call(GuestSeeder::class);
        }

        $guest = Guest::first();
        $room = Room::first();

        if (! $guest || ! $room) {
            return;
        }

        Booking::firstOrCreate(
            [
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'check_in_date' => now()->toDateString(),
                'check_out_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'status' => 'reserved',
                'total_amount' => $room->roomType->base_price * 2,
                'amount_paid' => 0,
                'notes' => 'Sample booking created by seeder.',
            ],
        );
    }
}
