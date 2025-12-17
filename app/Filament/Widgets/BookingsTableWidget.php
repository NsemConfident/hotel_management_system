<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BookingsTableWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';
    

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['guest', 'room.roomType'])
                    ->latest('check_in_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking #')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guest.full_name')
                    ->label('Guest')
                    ->searchable(['guest.first_name', 'guest.last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('room.roomType.name')
                    ->label('Room Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reserved' => 'info',
                        'checked_in' => 'success',
                        'checked_out' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('USD')
                    ->sortable()
                    ->color(fn ($record) => $record->amount_paid >= $record->total_amount ? 'success' : 'warning'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'reserved' => 'Reserved',
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Bookings')
                    ->query(fn ($query) => $query->where('check_in_date', '>=', now()->toDateString())
                        ->whereIn('status', ['reserved', 'checked_in'])),
                Tables\Filters\Filter::make('today_checkins')
                    ->label('Today\'s Check-ins')
                    ->query(fn ($query) => $query->where('check_in_date', now()->toDateString())
                        ->where('status', '!=', 'cancelled')),
                Tables\Filters\Filter::make('today_checkouts')
                    ->label('Today\'s Check-outs')
                    ->query(fn ($query) => $query->where('check_out_date', now()->toDateString())
                        ->where('status', '!=', 'cancelled')),
            ])
            ->defaultSort('check_in_date', 'desc')
            ->paginated([10, 25, 50])
            ->heading('Recent Bookings')
            ->description('View and manage all hotel bookings');
    }
}
