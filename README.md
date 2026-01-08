# Hotel Management System (HotMag)

A comprehensive hotel management system built with Laravel, Filament, and Livewire. This system provides a complete solution for managing hotel operations including room bookings, guest management, staff administration, and automated email notifications.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [User Roles & Permissions](#user-roles--permissions)
- [Key Features](#key-features)
- [Email Notifications](#email-notifications)
- [Development](#development)
- [Testing](#testing)
- [Database](#database)
- [API Endpoints](#api-endpoints)
- [Troubleshooting](#troubleshooting)

## Features

### Guest Portal
- **Guest Registration & Authentication**: Secure guest registration and login system
- **Room Booking**: Browse available rooms, select dates, and create bookings
- **Booking Management**: View, track, and cancel bookings
- **Profile Management**: Update personal information and preferences
- **Loyalty Program**: Track loyalty points and view loyalty tier status
- **Booking History**: View all past and current bookings

### Admin Dashboard (Filament)
- **Multi-Panel System**: Separate admin, manager, and receptionist panels
- **Room Management**: Create, edit, and manage rooms and room types
- **Guest Management**: Comprehensive guest profiles with booking history
- **Booking Management**: Full booking lifecycle management (create, check-in, check-out, cancel)
- **Payment Tracking**: Record and track booking payments
- **User Management**: Manage staff users with role-based access
- **Analytics & Reports**: 
  - Booking statistics and trends
  - Revenue tracking and charts
  - Occupancy rates and forecasts
  - Room type popularity analysis
  - Guest loyalty metrics
  - Performance metrics

### Automated Features
- **Email Notifications**: 
  - Booking confirmations
  - Check-in reminders (24 hours before)
  - Check-out reminders (24 hours before)
  - Cancellation confirmations
- **Scheduled Tasks**: Automated reminder emails via Laravel scheduler
- **Loyalty Points**: Automatic calculation and tracking

## Technology Stack

### Backend
- **Laravel 12**: PHP framework
- **PHP 8.2+**: Programming language
- **Filament 3**: Admin panel framework
- **Laravel Fortify**: Authentication system
- **Livewire Volt**: Reactive components
- **Livewire Flux**: UI components

### Frontend
- **Tailwind CSS 4**: Utility-first CSS framework
- **Vite**: Build tool and dev server
- **Blade**: Laravel templating engine
- **Alpine.js**: Lightweight JavaScript framework (via Livewire)

### Database
- **SQLite**: Default database (can be configured for MySQL/PostgreSQL)
- **Eloquent ORM**: Database abstraction layer

### Testing
- **Pest PHP**: Testing framework
- **PHPUnit**: Unit testing

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd hotmag
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 5. Build Assets

```bash
# Build for production
npm run build

# Or run in development mode
npm run dev
```

### 6. Start Development Server

```bash
# Start Laravel server
php artisan serve

# Or use the dev script (includes queue worker and Vite)
composer run dev
```

The application will be available at `http://localhost:8000`

## Configuration

### Environment Variables

Key environment variables to configure in `.env`:

```env
APP_NAME="Hotel Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
# Or for MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hotmag
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration (see Email Notifications section)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourhotel.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Default Login Credentials

After seeding, you can login with:
- **Email**: `admin@example.com`
- **Password**: `password`

**⚠️ Important**: Change the default password in production!

## Project Structure

```
hotmag/
├── app/
│   ├── Actions/              # Fortify actions
│   ├── Console/               # Artisan commands
│   │   └── Commands/
│   │       └── SendBookingReminders.php
│   ├── Filament/              # Filament admin panels
│   │   ├── Manager/           # Manager panel resources
│   │   ├── Resources/         # CRUD resources
│   │   │   ├── BookingResource.php
│   │   │   ├── GuestResource.php
│   │   │   ├── RoomResource.php
│   │   │   ├── RoomTypeResource.php
│   │   │   ├── UserResource.php
│   │   │   └── RoleResource.php
│   │   └── Widgets/           # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Guest/         # Guest portal controllers
│   │   └── Middleware/        # Custom middleware
│   ├── Livewire/              # Livewire components
│   ├── Mail/                  # Mailable classes
│   │   ├── BookingConfirmation.php
│   │   ├── BookingReminder.php
│   │   └── BookingCancellation.php
│   ├── Models/                # Eloquent models
│   │   ├── Booking.php
│   │   ├── Guest.php
│   │   ├── Room.php
│   │   ├── RoomType.php
│   │   ├── User.php
│   │   └── Role.php
│   ├── Policies/              # Authorization policies
│   └── Providers/             # Service providers
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/
│   │   ├── guest/             # Guest portal views
│   │   ├── emails/            # Email templates
│   │   └── layouts/           # Blade layouts
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript
├── routes/
│   └── web.php                # Web routes
├── tests/                     # Test files
└── public/                    # Public assets
```

## User Roles & Permissions

The system supports three main user roles:

### Admin
- Full system access
- Can manage all resources (rooms, bookings, guests, users)
- Can delete critical data
- Can manage user accounts and roles
- Access to all panels and settings

### Manager
- Can manage bookings, rooms, guests, and room types
- Can view analytics and reports
- Cannot delete critical data
- Cannot manage users
- Access to manager panel

### Receptionist
- Can view and manage bookings
- Can check-in and check-out guests
- Can view guests and rooms
- Limited access to settings
- Access to receptionist panel

### Guest
- Separate authentication system (not Laravel users)
- Can create and manage their own bookings
- Can view booking history
- Can update profile information
- Access to guest portal

## Key Features

### Room Management
- **Room Types**: Define different room categories (Single, Double, Suite, etc.)
- **Room Inventory**: Track individual rooms with room numbers and floors
- **Room Status**: Available, Occupied, Maintenance, Out of Order
- **Pricing**: Base price per night for each room type

### Booking System
- **Booking Statuses**: 
  - `reserved`: Booking confirmed, not yet checked in
  - `checked_in`: Guest has checked in
  - `checked_out`: Guest has checked out
  - `cancelled`: Booking cancelled
- **Date Validation**: Prevents double-booking and invalid date ranges
- **Automatic Pricing**: Calculates total based on room type and number of nights
- **Payment Tracking**: Record partial and full payments

### Guest Management
- **Comprehensive Profiles**: 
  - Personal information (name, email, phone, address)
  - ID number and nationality
  - Date of birth and preferred language
  - Special requests and preferences
  - VIP status
- **Loyalty Program**:
  - Points accumulation (1 point per dollar spent)
  - Loyalty tiers: Bronze, Silver, Gold, Platinum
  - Automatic point calculation
- **Booking History**: Track all bookings, total spent, and nights stayed

### Analytics & Reporting
- **Booking Statistics**: Total bookings, revenue, occupancy rates
- **Trends**: Booking trends over time
- **Charts**: Visual representation of revenue, occupancy, and room type popularity
- **Performance Metrics**: Key performance indicators for managers
- **Guest Loyalty**: Track guest loyalty and repeat bookings

## Email Notifications

The system sends automated emails for various booking events. See [README_EMAIL_SETUP.md](README_EMAIL_SETUP.md) for detailed email configuration instructions.

### Email Types
1. **Booking Confirmation**: Sent immediately when a booking is created
2. **Check-in Reminder**: Sent 24 hours before check-in date
3. **Check-out Reminder**: Sent 24 hours before check-out date
4. **Booking Cancellation**: Sent when a booking is cancelled

### Scheduled Reminders

Reminders are sent automatically via Laravel's task scheduler. To enable:

1. **Set up Cron Job** (Production):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

2. **Manual Testing**:
```bash
php artisan bookings:send-reminders
```

3. **Configure Scheduler** in `app/Console/Kernel.php` (if not already configured):
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('bookings:send-reminders')
        ->dailyAt('09:00');
}
```

## Development

### Running the Development Server

```bash
# Start all services (server, queue, vite)
composer run dev

# Or individually:
php artisan serve          # Laravel server
php artisan queue:work     # Queue worker
npm run dev                # Vite dev server
```

### Code Style

The project uses Laravel Pint for code formatting:

```bash
# Format code
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Database Migrations

```bash
# Create a new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh --seed
```

### Creating Resources

```bash
# Create a Filament resource
php artisan make:filament-resource ModelName

# Create a Livewire component
php artisan make:livewire ComponentName

# Create a controller
php artisan make:controller ControllerName
```

## Testing

The project uses Pest PHP for testing:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run with coverage
php artisan test --coverage
```

### Test Structure
- `tests/Feature/`: Feature tests (authentication, dashboard, etc.)
- `tests/Unit/`: Unit tests

## Database

### Models & Relationships

- **User** → belongsTo **Role**
- **Guest** → hasMany **Booking**
- **Room** → belongsTo **RoomType**, hasMany **Booking**
- **RoomType** → hasMany **Room**
- **Booking** → belongsTo **Room**, belongsTo **Guest**, hasMany **BookingPayment**
- **BookingPayment** → belongsTo **Booking**
- **Role** → hasMany **User**

### Key Tables
- `users`: Staff users
- `roles`: User roles (admin, manager, receptionist)
- `guests`: Guest profiles
- `room_types`: Room categories
- `rooms`: Individual rooms
- `bookings`: Booking records
- `booking_payments`: Payment transactions

## API Endpoints

### Guest Portal API

- `GET /guest/api/available-rooms`: Get available rooms for selected dates
  - Parameters: `room_type_id`, `check_in_date`, `check_out_date`

### Guest Portal Routes

- `GET /guest/login`: Guest login page
- `POST /guest/login`: Guest login
- `GET /guest/register`: Guest registration
- `POST /guest/register`: Guest registration
- `POST /guest/logout`: Guest logout
- `GET /guest/dashboard`: Guest dashboard
- `GET /guest/bookings`: List bookings
- `GET /guest/bookings/create`: Create booking form
- `POST /guest/bookings`: Store booking
- `GET /guest/bookings/{id}`: View booking
- `POST /guest/bookings/{id}/cancel`: Cancel booking
- `GET /guest/profile`: View profile
- `GET /guest/profile/edit`: Edit profile form
- `POST /guest/profile`: Update profile

### Admin Panels

- `/admin`: Admin panel (Filament)
- `/manager`: Manager panel (Filament)
- `/receptionist`: Receptionist panel (Filament)

## Deployment

### Production Checklist

Before deploying to production, ensure:

1. **Environment Configuration**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate new `APP_KEY` if not already set
   - Configure production database (MySQL/PostgreSQL recommended)
   - Set secure `APP_URL`

2. **Security**
   - Change default admin password
   - Use strong database passwords
   - Configure HTTPS/SSL
   - Review and restrict file permissions
   - Enable CSRF protection (already enabled)
   - Review `.env` file permissions (should not be publicly accessible)

3. **Performance**
   - Enable OPcache
   - Configure queue workers for email sending
   - Set up Redis/Memcached for caching
   - Optimize assets: `npm run build`
   - Run `php artisan config:cache`
   - Run `php artisan route:cache`
   - Run `php artisan view:cache`

4. **Cron Jobs**
   - Set up scheduler: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`
   - Set up queue worker: `php artisan queue:work --daemon` (or use supervisor)

5. **Database**
   - Run migrations: `php artisan migrate --force`
   - Seed initial data if needed: `php artisan db:seed --class=RoleSeeder`

6. **File Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

### Deployment Steps

1. **Clone and Install**
   ```bash
   git clone <repository-url>
   cd hotmag
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Edit .env with production values
   ```

3. **Database Setup**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=RoleSeeder
   ```

4. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

6. **Configure Web Server**
   - Point document root to `public/` directory
   - Configure URL rewriting (Apache `.htaccess` or Nginx config)

### Queue Workers

For production, use a process manager like Supervisor:

```ini
[program:hotmag-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-project/storage/logs/worker.log
```

## Troubleshooting

### Common Issues

1. **Email not sending?**
   - Check `.env` mail configuration
   - Verify SMTP credentials
   - Check `storage/logs/laravel.log`
   - Test with `php artisan tinker`

2. **Reminders not being sent?**
   - Ensure cron job is set up correctly
   - Check timezone settings
   - Manually test: `php artisan bookings:send-reminders`

3. **Database errors?**
   - Ensure database file exists: `database/database.sqlite`
   - Run migrations: `php artisan migrate:fresh --seed`
   - Check file permissions

4. **Assets not loading?**
   - Run `npm install`
   - Build assets: `npm run build`
   - Clear cache: `php artisan cache:clear`

5. **Permission errors?**
   - Check storage permissions: `chmod -R 775 storage bootstrap/cache`
   - Ensure web server has write access

### Clearing Caches

```bash
# Clear all caches
php artisan optimize:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```

## Additional Documentation

- [Email Setup Guide](README_EMAIL_SETUP.md) - Detailed email configuration
- [Mailtrap Setup](SETUP_MAILTRAP.md) - Quick Mailtrap configuration

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please refer to the project repository or contact the development team.

---

**Note**: This is a student project for educational purposes. Ensure all security best practices are followed before deploying to production.

