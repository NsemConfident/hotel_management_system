<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $reminderType; // 'checkin' or 'checkout'

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $reminderType = 'checkin')
    {
        $this->booking = $booking;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->reminderType === 'checkin' 
            ? 'Reminder: Your Check-in is Tomorrow - Booking #' . $this->booking->id
            : 'Reminder: Your Check-out is Tomorrow - Booking #' . $this->booking->id;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-reminder',
            with: [
                'booking' => $this->booking,
                'guest' => $this->booking->guest,
                'room' => $this->booking->room,
                'reminderType' => $this->reminderType,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

