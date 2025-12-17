<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Validate no overlapping bookings (excluding current booking)
        $hasOverlap = Booking::where('room_id', $data['room_id'])
            ->where('id', '!=', $this->record->id)
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

    protected function afterSave(): void
    {
        // Update room status based on booking status
        $room = $this->record->room;
        $status = $this->record->status;
        $guest = $this->record->guest;

        if ($status === 'checked_in') {
            $room->update(['status' => 'occupied']);
            // Update guest's last visit
            $guest->update(['last_visit_at' => now()]);
        } elseif ($status === 'checked_out') {
            // Only set to available if no other active bookings
            $hasActiveBooking = Booking::where('room_id', $room->id)
                ->where('id', '!=', $this->record->id)
                ->whereIn('status', ['reserved', 'checked_in'])
                ->exists();

            if (!$hasActiveBooking) {
                $room->update(['status' => 'available']);
            }

            // Award loyalty points if not already awarded
            if (!$this->record->wasChanged('status') || $this->record->getOriginal('status') !== 'checked_out') {
                $points = $guest->calculateLoyaltyPointsFromBooking($this->record);
                $guest->addLoyaltyPoints($points, "Booking #{$this->record->id} completed");
            }
        } elseif ($status === 'cancelled') {
            // Only set to available if no other active bookings
            $hasActiveBooking = Booking::where('room_id', $room->id)
                ->where('id', '!=', $this->record->id)
                ->whereIn('status', ['reserved', 'checked_in'])
                ->exists();

            if (!$hasActiveBooking) {
                $room->update(['status' => 'available']);
            }
        }
    }
}
