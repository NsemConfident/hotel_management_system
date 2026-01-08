<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
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
            background-color: {{ $reminderType === 'checkin' ? '#10b981' : '#f59e0b' }};
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
        .reminder-box {
            background-color: {{ $reminderType === 'checkin' ? '#d1fae5' : '#fef3c7' }};
            border-left: 4px solid {{ $reminderType === 'checkin' ? '#10b981' : '#f59e0b' }};
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            @if($reminderType === 'checkin')
                Check-in Reminder
            @else
                Check-out Reminder
            @endif
        </h1>
    </div>

    <div class="content">
        <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>

        <div class="reminder-box">
            <p style="margin: 0; font-weight: bold;">
                @if($reminderType === 'checkin')
                    Your check-in is tomorrow ({{ $booking->check_in_date->format('l, F d, Y') }})!
                @else
                    Your check-out is tomorrow ({{ $booking->check_out_date->format('l, F d, Y') }})!
                @endif
            </p>
        </div>

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
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value"><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
            </div>
        </div>

        @if($reminderType === 'checkin')
        <p><strong>Check-in Information:</strong></p>
        <ul>
            <li>Check-in time: 3:00 PM</li>
            <li>Please bring a valid ID</li>
            <li>Early check-in may be available upon request (subject to availability)</li>
            <li>If you need to modify your booking, please contact us as soon as possible</li>
        </ul>
        @else
        <p><strong>Check-out Information:</strong></p>
        <ul>
            <li>Check-out time: 11:00 AM</li>
            <li>Late check-out may be available upon request (subject to availability)</li>
            <li>Please ensure all personal belongings are collected</li>
            <li>Any outstanding payments should be settled at check-out</li>
        </ul>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('guest.bookings.show', $booking) }}" class="button">View Booking Details</a>
        </div>

        <p>We look forward to {{ $reminderType === 'checkin' ? 'welcoming you' : 'serving you again' }}!</p>

        <p>Best regards,<br>
        Hotel Management Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Hotel Management System. All rights reserved.</p>
    </div>
</body>
</html>

