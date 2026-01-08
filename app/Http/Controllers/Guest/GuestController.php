<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GuestController extends Controller
{
    protected function getGuest()
    {
        $guestId = Session::get('guest_id');
        if (!$guestId) {
            return null;
        }
        return Guest::find($guestId);
    }

    public function dashboard()
    {
        $guest = $this->getGuest();
        if (!$guest) {
            return redirect()->route('guest.login');
        }

        $bookings = $guest->bookings()
            ->with(['room.roomType'])
            ->orderBy('check_in_date', 'desc')
            ->limit(5)
            ->get();

        $activeBookings = $guest->bookings()
            ->whereIn('status', ['reserved', 'checked_in'])
            ->count();

        $totalBookings = $guest->bookings()->count();
        $totalSpent = $guest->total_spent;
        $loyaltyPoints = $guest->loyalty_points;

        return view('guest.dashboard', compact(
            'guest',
            'bookings',
            'activeBookings',
            'totalBookings',
            'totalSpent',
            'loyaltyPoints'
        ));
    }

    public function bookings()
    {
        $guest = $this->getGuest();
        $bookings = $guest->bookings()
            ->with(['room.roomType', 'payments'])
            ->orderBy('check_in_date', 'desc')
            ->paginate(10);

        return view('guest.bookings.index', compact('guest', 'bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $guest = $this->getGuest();
        
        // Ensure the booking belongs to this guest
        if ($booking->guest_id !== $guest->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        $booking->load(['room.roomType', 'payments']);

        return view('guest.bookings.show', compact('guest', 'booking'));
    }

    public function createBooking()
    {
        $guest = $this->getGuest();
        $roomTypes = RoomType::with('rooms')
            ->whereHas('rooms', function($query) {
                $query->where('status', 'available');
            })
            ->get();

        return view('guest.bookings.create', compact('guest', 'roomTypes'));
    }

    public function storeBooking(Request $request)
    {
        $guest = $this->getGuest();

        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Verify room is available
        $room = Room::findOrFail($validated['room_id']);
        if ($room->status !== 'available') {
            return back()->withInput()
                ->with('error', 'Selected room is not available.');
        }

        // Check for overlapping bookings
        $hasOverlap = Booking::where('room_id', $validated['room_id'])
            ->whereIn('status', ['reserved', 'checked_in'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                    ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('check_in_date', '<=', $validated['check_in_date'])
                            ->where('check_out_date', '>=', $validated['check_out_date']);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withInput()
                ->with('error', 'Room is already booked for the selected dates. Please choose different dates.');
        }

        // Calculate total amount
        $roomType = RoomType::findOrFail($validated['room_type_id']);
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $roomType->base_price * $nights;

        // Create booking
        $booking = Booking::create([
            'guest_id' => $guest->id,
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'status' => 'reserved',
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'notes' => $validated['special_requests'] ?? null,
        ]);

        // Update guest preferences if provided
        if ($validated['special_requests'] ?? null) {
            $preferences = $guest->preferences ?? [];
            if (!in_array($validated['special_requests'], $preferences)) {
                $preferences[] = $validated['special_requests'];
                $guest->update(['preferences' => $preferences]);
            }
        }

        return redirect()->route('guest.bookings.show', $booking)
            ->with('success', 'Booking created successfully! Your booking reference is #' . $booking->id);
    }

    public function cancelBooking(Booking $booking)
    {
        $guest = $this->getGuest();
        
        if ($booking->guest_id !== $guest->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($booking->status, ['reserved', 'checked_in'])) {
            return back()->with('error', 'Only reserved or checked-in bookings can be cancelled.');
        }

        $wasCheckedIn = $booking->status === 'checked_in';
        $booking->update(['status' => 'cancelled']);

        // If was checked in, free up the room
        if ($wasCheckedIn) {
            $booking->room->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function profile()
    {
        $guest = $this->getGuest();
        return view('guest.profile', compact('guest'));
    }

    public function updateProfile(Request $request)
    {
        $guest = $this->getGuest();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string|max:255',
        ]);

        $guest->update($validated);

        Session::put('guest_name', $guest->full_name);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $bookedRoomIds = Booking::whereIn('status', ['reserved', 'checked_in'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('check_in_date', '<=', $request->check_in_date)
                            ->where('check_out_date', '>=', $request->check_out_date);
                    });
            })
            ->pluck('room_id');

        $availableRooms = Room::where('room_type_id', $request->room_type_id)
            ->where('status', 'available')
            ->whereNotIn('id', $bookedRoomIds)
            ->get();

        return response()->json($availableRooms);
    }
}

