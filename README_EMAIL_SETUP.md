# Email Notifications Setup

This document explains how to configure email notifications for the hotel management system.

## Features

The system sends automated emails for:
- **Booking Confirmations**: Sent immediately when a guest creates a booking
- **Check-in Reminders**: Sent 24 hours before check-in date
- **Check-out Reminders**: Sent 24 hours before check-out date
- **Booking Cancellations**: Sent when a booking is cancelled

## Configuration

### 1. Environment Variables

Add the following to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourhotel.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Mail Service Providers

#### Option A: Mailtrap (Development/Testing)
- Sign up at https://mailtrap.io
- Use their SMTP credentials in your `.env` file
- Perfect for testing without sending real emails

#### Option B: Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

**Note**: For Gmail, you need to generate an "App Password" in your Google Account settings.

#### Option C: SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
```

#### Option D: Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your_domain.mailgun.org
MAILGUN_SECRET=your_mailgun_secret
```

### 3. Testing Email Configuration

Test your email configuration:

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email', function ($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

## Scheduled Reminders

The system automatically sends reminders daily at 9:00 AM UTC. To ensure this works:

### 1. Set up Cron Job (Production)

Add this to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Manual Testing

You can manually trigger the reminder command:

```bash
php artisan bookings:send-reminders
```

## Email Templates

Email templates are located in:
- `resources/views/emails/booking-confirmation.blade.php`
- `resources/views/emails/booking-reminder.blade.php`
- `resources/views/emails/booking-cancellation.blade.php`

You can customize these templates to match your hotel's branding.

## Troubleshooting

### Emails not sending?

1. **Check logs**: `storage/logs/laravel.log`
2. **Verify .env configuration**: Ensure all MAIL_* variables are set correctly
3. **Test connection**: Use `php artisan tinker` to test email sending
4. **Check queue**: If using queues, ensure workers are running

### Reminders not being sent?

1. **Check scheduler**: Ensure cron job is set up correctly
2. **Verify timezone**: Check that your server timezone matches your application timezone
3. **Manual test**: Run `php artisan bookings:send-reminders` manually

## Queue Configuration (Optional)

For better performance, you can queue emails:

1. Set `QUEUE_CONNECTION=database` in `.env`
2. Run migrations: `php artisan queue:table` and `php artisan migrate`
3. Start queue worker: `php artisan queue:work`

Then update the Mail sending in controllers to use queues:
```php
Mail::to($email)->queue(new BookingConfirmation($booking));
```

## Support

For issues or questions, check the Laravel Mail documentation:
https://laravel.com/docs/mail

