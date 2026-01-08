<?php

namespace App\Console\Commands;

use App\Mail\BookingReminder;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming check-ins and check-outs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending booking reminders...');

        $today = now()->startOfDay();
        $tomorrow = $today->copy()->addDay();

        // Find bookings with check-in tomorrow
        $checkInBookings = Booking::where('check_in_date', $tomorrow->format('Y-m-d'))
            ->whereIn('status', ['reserved', 'checked_in'])
            ->with(['guest', 'room.roomType'])
            ->get();

        // Find bookings with check-out tomorrow
        $checkOutBookings = Booking::where('check_out_date', $tomorrow->format('Y-m-d'))
            ->whereIn('status', ['checked_in'])
            ->with(['guest', 'room.roomType'])
            ->get();

        $sentCount = 0;
        $errorCount = 0;

        // Send check-in reminders
        foreach ($checkInBookings as $booking) {
            if ($booking->guest && $booking->guest->email) {
                try {
                    Mail::to($booking->guest->email)->send(new BookingReminder($booking, 'checkin'));
                    $sentCount++;
                    $this->info("Sent check-in reminder for booking #{$booking->id} to {$booking->guest->email}");
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("Failed to send check-in reminder for booking #{$booking->id}: " . $e->getMessage());
                }
            }
        }

        // Send check-out reminders
        foreach ($checkOutBookings as $booking) {
            if ($booking->guest && $booking->guest->email) {
                try {
                    Mail::to($booking->guest->email)->send(new BookingReminder($booking, 'checkout'));
                    $sentCount++;
                    $this->info("Sent check-out reminder for booking #{$booking->id} to {$booking->guest->email}");
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("Failed to send check-out reminder for booking #{$booking->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Reminder sending completed. Sent: {$sentCount}, Errors: {$errorCount}");

        return Command::SUCCESS;
    }
}

