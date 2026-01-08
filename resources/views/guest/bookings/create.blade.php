@extends('layouts.guest_layout')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('guest.bookings.index') }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-block">
                ← Back to Bookings
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Booking</h1>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <form action="{{ route('guest.bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                <div class="px-6 py-5 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="check_in_date" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                            <input type="date" name="check_in_date" id="check_in_date" required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('check_in_date') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('check_in_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="check_out_date" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                            <input type="date" name="check_out_date" id="check_out_date" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                value="{{ old('check_out_date') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('check_out_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="room_type_id" class="block text-sm font-medium text-gray-700">Room Type</label>
                        <select name="room_type_id" id="room_type_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select a room type</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" data-price="{{ $roomType->base_price }}"
                                    {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }} - ${{ number_format($roomType->base_price, 2) }}/night
                                    (Max {{ $roomType->max_occupancy }} guests)
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700">Available Rooms</label>
                        <select name="room_id" id="room_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            disabled>
                            <option value="">First select room type and dates</option>
                        </select>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500" id="room-help">Select your check-in date, check-out date, and room type to see available rooms.</p>
                    </div>

                    <div>
                        <label for="special_requests" class="block text-sm font-medium text-gray-700">Special Requests (Optional)</label>
                        <textarea name="special_requests" id="special_requests" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Any special requests or preferences...">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4" id="total-display" style="display: none;">
                        <h3 class="text-sm font-medium text-blue-900 mb-2">Booking Summary</h3>
                        <div class="space-y-1 text-sm text-blue-800">
                            <p id="nights-display"></p>
                            <p id="rate-display"></p>
                            <p class="font-bold text-lg" id="total-amount-display"></p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('guest.bookings.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const roomTypeSelect = document.getElementById('room_type_id');
    const roomSelect = document.getElementById('room_id');
    const totalDisplay = document.getElementById('total-display');

    function updateAvailableRooms() {
        const checkIn = checkInDate.value;
        const checkOut = checkOutDate.value;
        const roomTypeId = roomTypeSelect.value;

        if (!checkIn || !checkOut || !roomTypeId) {
            roomSelect.innerHTML = '<option value="">First select room type and dates</option>';
            roomSelect.disabled = true;
            totalDisplay.style.display = 'none';
            return;
        }

        if (new Date(checkOut) <= new Date(checkIn)) {
            roomSelect.innerHTML = '<option value="">Check-out date must be after check-in date</option>';
            roomSelect.disabled = true;
            totalDisplay.style.display = 'none';
            return;
        }

        // Fetch available rooms
        fetch(`{{ route('guest.api.available-rooms') }}?room_type_id=${roomTypeId}&check_in_date=${checkIn}&check_out_date=${checkOut}`)
            .then(response => response.json())
            .then(rooms => {
                roomSelect.innerHTML = '<option value="">Select a room</option>';
                if (rooms.length > 0) {
                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `Room ${room.room_number} - Floor ${room.floor}`;
                        roomSelect.appendChild(option);
                    });
                    roomSelect.disabled = false;
                    calculateTotal();
                } else {
                    roomSelect.innerHTML = '<option value="">No rooms available for selected dates</option>';
                    roomSelect.disabled = true;
                    totalDisplay.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                roomSelect.disabled = true;
            });
    }

    function calculateTotal() {
        const checkIn = new Date(checkInDate.value);
        const checkOut = new Date(checkOutDate.value);
        const roomTypeId = roomTypeSelect.value;
        
        if (!checkIn || !checkOut || !roomTypeId || checkOut <= checkIn) {
            totalDisplay.style.display = 'none';
            return;
        }

        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        const pricePerNight = parseFloat(selectedOption.dataset.price) || 0;
        const total = nights * pricePerNight;

        if (total > 0) {
            document.getElementById('nights-display').textContent = `${nights} night(s)`;
            document.getElementById('rate-display').textContent = `$${pricePerNight.toFixed(2)} per night`;
            document.getElementById('total-amount-display').textContent = `Total: $${total.toFixed(2)}`;
            totalDisplay.style.display = 'block';
        } else {
            totalDisplay.style.display = 'none';
        }
    }

    checkInDate.addEventListener('change', function() {
        if (checkOutDate.value && new Date(checkOutDate.value) <= new Date(this.value)) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutDate.value = nextDay.toISOString().split('T')[0];
        }
        checkOutDate.min = nextDay.toISOString().split('T')[0];
        updateAvailableRooms();
        calculateTotal();
    });

    checkOutDate.addEventListener('change', updateAvailableRooms);
    roomTypeSelect.addEventListener('change', updateAvailableRooms);
    roomSelect.addEventListener('change', calculateTotal);
});
</script>
@endsection

