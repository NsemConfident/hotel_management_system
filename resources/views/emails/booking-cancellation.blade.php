<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled</title>
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
            background-color: #ef4444;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Cancelled</h1>
    </div>

    <div class="content">
        <p>Dear {{ $guest->first_name }} {{ $guest->last_name }},</p>

        <p>This email confirms that your booking has been cancelled.</p>

        <div class="booking-details">
            <h2 style="margin-top: 0; color: #111827;">Cancelled Booking Details</h2>
            
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
                <span class="detail-value">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        <p><strong>Important Information:</strong></p>
        <ul>
            <li>Your booking has been successfully cancelled</li>
            <li>If you made any payments, refunds will be processed according to our cancellation policy</li>
            <li>If you have any questions about the cancellation, please contact us</li>
        </ul>

        <p>We're sorry to see you go. If you'd like to make a new booking, please visit our website or contact us directly.</p>

        <p>Best regards,<br>
        Hotel Management Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Hotel Management System. All rights reserved.</p>
    </div>
</body>
</html>

