<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .booking-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-reserved {
            background-color: #fef3c7;
            color: #92400e;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Confirmed!</h1>
        <p>Thank you for choosing our hotel</p>
    </div>

    <div class="content">
        <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>

        <p>We are pleased to confirm your booking. Your reservation has been successfully processed.</p>

        <div class="booking-details">
            <h2 style="margin-top: 0; color: #111827;">Booking Details</h2>
            
            <div class="detail-row">
                <span class="detail-label">Booking Reference:</span>
                <span class="detail-value"><strong>#{{ $booking->id }}</strong></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Room:</span>
                <span class="detail-value">Room {{ $room->room_number }} - {{ $room->roomType->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value">{{ $booking->check_in_date->format('l, F d, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value">{{ $booking->check_out_date->format('l, F d, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Duration:</span>
                <span class="detail-value">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night(s)</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value"><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-reserved">{{ ucfirst($booking->status) }}</span>
                </span>
            </div>

            @if($booking->notes)
            <div class="detail-row">
                <span class="detail-label">Special Requests:</span>
                <span class="detail-value">{{ $booking->notes }}</span>
            </div>
            @endif
        </div>

        <p><strong>Important Information:</strong></p>
        <ul>
            <li>Check-in time: 3:00 PM</li>
            <li>Check-out time: 11:00 AM</li>
            <li>Please bring a valid ID for check-in</li>
            <li>You will receive a reminder email 24 hours before your check-in</li>
        </ul>

        <p>If you have any questions or need to make changes to your booking, please contact us or log in to your account.</p>

        <div style="text-align: center;">
            <a href="{{ route('guest.bookings.show', $booking) }}" class="button">View Booking Details</a>
        </div>

        <p>We look forward to welcoming you!</p>

        <p>Best regards,<br>
        Hotel Management Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Hotel Management System. All rights reserved.</p>
    </div>
</body>
</html>

