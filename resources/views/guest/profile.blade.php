@extends('layouts.guest_layout')

@section('title', 'My Profile')

@section('content')
<div class="max-w-8xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <a href="{{ route('guest.profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profile
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 space-y-6">
                <!-- Personal Information Section -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">First Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $guest->first_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $guest->last_name }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $guest->email }}</p>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $guest->phone ?? 'Not provided' }}</p>
                    </div>

                    @if($guest->address)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $guest->address }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        @if($guest->date_of_birth)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $guest->date_of_birth->format('F d, Y') }}</p>
                        </div>
                        @endif

                        @if($guest->nationality)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nationality</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $guest->nationality }}</p>
                        </div>
                        @endif
                    </div>

                    @if($guest->preferred_language)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Preferred Language</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $guest->preferred_language }}</p>
                    </div>
                    @endif
                </div>

                <!-- Loyalty Information Section -->
                <div class="border-t border-gray-200 pt-6" id="loyalty">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Loyalty Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Loyalty Points</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($guest->loyalty_points) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Loyalty Tier</label>
                            <p class="mt-1 text-lg font-semibold text-blue-600">{{ $guest->loyalty_tier }}</p>
                        </div>
                    </div>
                    @if($guest->is_vip)
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            VIP Member
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Account Statistics Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Account Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Bookings</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">{{ $guest->bookings()->count() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Spent</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">${{ number_format($guest->total_spent, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Nights</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">{{ $guest->total_nights }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
