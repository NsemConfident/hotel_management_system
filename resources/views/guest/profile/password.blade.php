@extends('layouts.guest_layout')

@section('title', 'Change Password')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('guest.profile') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mb-4 inline-block flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Profile
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Change Password</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-colors duration-200">
            <form action="{{ route('guest.profile.password.update') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Update Your Password</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Please enter your current password and choose a new password.</p>
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password" id="password" required minlength="8"
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must be at least 8 characters long.</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            class="mt-1 py-2 px-3 border block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 space-x-3">
                        <a href="{{ route('guest.profile') }}" class="bg-white dark:bg-gray-700 dark:text-white py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 dark:bg-blue-700 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

