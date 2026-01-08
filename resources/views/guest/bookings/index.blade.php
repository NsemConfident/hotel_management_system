@extends('layouts.guest_layout')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-8xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
            <a href="{{ route('guest.bookings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                New Booking
            </a>
        </div>

        @if($bookings->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <li>
                        <a href="{{ route('guest.bookings.show', $booking) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            Booking #{{ $booking->id }}
                                        </p>
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($booking->status === 'reserved') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'checked_in') bg-green-100 text-green-800
                                            @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{ number_format($booking->total_amount, 2) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Room {{ $booking->room->room_number }} - {{ $booking->room->roomType->name }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>
                                            {{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new booking.</p>
                <div class="mt-6">
                    <a href="{{ route('guest.bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        New Booking
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

