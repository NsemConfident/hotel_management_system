<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Guest Portal Routes
Route::prefix('guest')->name('guest.')->group(function () {
    // Authentication routes (public)
    Route::get('/login', [App\Http\Controllers\Guest\GuestAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Guest\GuestAuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Guest\GuestAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\Guest\GuestAuthController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Guest\GuestAuthController::class, 'logout'])->name('logout');

    // Protected routes (require guest authentication)
    Route::middleware(['guest.auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Guest\GuestController::class, 'dashboard'])->name('dashboard');
        Route::get('/bookings', [App\Http\Controllers\Guest\GuestController::class, 'bookings'])->name('bookings.index');
        Route::get('/bookings/create', [App\Http\Controllers\Guest\GuestController::class, 'createBooking'])->name('bookings.create');
        Route::post('/bookings', [App\Http\Controllers\Guest\GuestController::class, 'storeBooking'])->name('bookings.store');
        Route::get('/bookings/{booking}', [App\Http\Controllers\Guest\GuestController::class, 'showBooking'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Guest\GuestController::class, 'cancelBooking'])->name('bookings.cancel');
        Route::get('/profile', [App\Http\Controllers\Guest\GuestController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [App\Http\Controllers\Guest\GuestController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile', [App\Http\Controllers\Guest\GuestController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/password', [App\Http\Controllers\Guest\GuestController::class, 'showPasswordForm'])->name('profile.password');
        Route::post('/profile/password', [App\Http\Controllers\Guest\GuestController::class, 'updatePassword'])->name('profile.password.update');
        Route::get('/api/available-rooms', [App\Http\Controllers\Guest\GuestController::class, 'getAvailableRooms'])->name('api.available-rooms');
    });
});
