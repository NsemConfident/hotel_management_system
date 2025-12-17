<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure amount_paid is set
        $data['amount_paid'] = $data['amount_paid'] ?? 0;

        // Validate no overlapping bookings
        $hasOverlap = Booking::where('room_id', $data['room_id'])
            ->where('id', '!=', $this->record->id ?? 0)
            ->whereIn('status', ['reserved', 'checked_in'])
            ->where(function (Builder $query) use ($data) {
                $query->whereBetween('check_in_date', [$data['check_in_date'], $data['check_out_date']])
                    ->orWhereBetween('check_out_date', [$data['check_in_date'], $data['check_out_date']])
                    ->orWhere(function (Builder $q) use ($data) {
                        $q->where('check_in_date', '<=', $data['check_in_date'])
                            ->where('check_out_date', '>=', $data['check_out_date']);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            Notification::make()
                ->title('Room is already booked for these dates')
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Update room status if booking is checked in
        if ($this->record->status === 'checked_in') {
            $this->record->room->update(['status' => 'occupied']);
        }
    }
}
