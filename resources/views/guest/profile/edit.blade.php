@extends('layouts.guest_layout')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('guest.profile') }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-block flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Profile
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-colors duration-200">
            <form action="{{ route('guest.profile.update') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                            <input type="text" name="first_name" id="first_name" required
                                value="{{ old('first_name', $guest->first_name) }}"
                                class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required
                                value="{{ old('last_name', $guest->last_name) }}"
                                class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ $guest->email }}" disabled
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Email cannot be changed. Please contact support if needed.</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="tel" name="phone" id="phone" required
                            value="{{ old('phone', $guest->phone) }}"
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('address', $guest->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                value="{{ old('date_of_birth', $guest->date_of_birth?->format('Y-m-d')) }}"
                                class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                            <input type="text" name="nationality" id="nationality"
                                value="{{ old('nationality', $guest->nationality) }}"
                                class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="preferred_language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Language</label>
                        <input type="text" name="preferred_language" id="preferred_language"
                            value="{{ old('preferred_language', $guest->preferred_language) }}"
                            placeholder="e.g., English, Spanish, French"
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('preferred_language')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 space-x-3">
                        <a href="{{ route('guest.profile') }}" class="bg-white dark:bg-gray-700 dark:text-white py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 dark:bg-blue-700 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

