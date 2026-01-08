@extends('layouts.guest_layout')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('guest.bookings.index') }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-block">
                ← Back to Bookings
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Booking Details #{{ $booking->id }}</h1>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Booking Information</h2>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($booking->status === 'reserved') bg-yellow-100 text-yellow-800
                        @elseif($booking->status === 'checked_in') bg-green-100 text-green-800
                        @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            </div>
            <div class="px-6 py-5 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Room Details</h3>
                        <p class="text-lg font-medium text-gray-900">Room {{ $booking->room->room_number }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->room->roomType->name }}</p>
                        <p class="text-sm text-gray-500">Floor {{ $booking->room->floor }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Dates</h3>
                        <p class="text-sm text-gray-900">
                            <strong>Check-in:</strong> {{ $booking->check_in_date->format('F d, Y') }}
                        </p>
                        <p class="text-sm text-gray-900">
                            <strong>Check-out:</strong> {{ $booking->check_out_date->format('F d, Y') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night(s)
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">Payment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="text-2xl font-bold @if($booking->amount_paid >= $booking->total_amount) text-green-600 @else text-yellow-600 @endif">
                                ${{ number_format($booking->amount_paid, 2) }}
                            </p>
                            @if($booking->amount_paid < $booking->total_amount)
                                <p class="text-sm text-red-600 mt-1">
                                    Balance: ${{ number_format($booking->total_amount - $booking->amount_paid, 2) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($booking->notes)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Special Requests</h3>
                    <p class="text-sm text-gray-900">{{ $booking->notes }}</p>
                </div>
                @endif

                @if($booking->payments->count() > 0)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">Payment History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($booking->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->paid_at?->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $payment->method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->reference ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if(in_array($booking->status, ['reserved', 'checked_in']))
                <div class="border-t border-gray-200 pt-6">
                    <form method="POST" action="{{ route('guest.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                            Cancel Booking
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

