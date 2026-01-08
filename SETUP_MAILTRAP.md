# Mailtrap SMTP Configuration

## Quick Setup

Add these lines to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=54ea79ac7b36cf
MAIL_PASSWORD=your_full_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourhotel.com
MAIL_FROM_NAME="Hotel Management"
```

## Steps:

1. Open your `.env` file
2. Find or add the `MAIL_*` configuration section
3. Update with your Mailtrap credentials:
   - **MAIL_HOST**: `sandbox.smtp.mailtrap.io`
   - **MAIL_PORT**: `2525` (or 587, 465, 25)
   - **MAIL_USERNAME**: `54ea79ac7b36cf`
   - **MAIL_PASSWORD**: Your full Mailtrap password (replace the masked part)
   - **MAIL_ENCRYPTION**: `tls`
4. Save the file
5. Clear config cache: `php artisan config:clear`

## Testing

After configuration, test by creating a booking. The confirmation email will appear in your Mailtrap inbox.

You can also test manually:
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email', function ($message) {
    $message->to('njocknsem@gmail.com')
            ->subject('Test Email');
});
```

Check your Mailtrap inbox to see the email!

