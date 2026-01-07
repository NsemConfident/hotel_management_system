<?php

namespace App\Filament\Widgets\Receptionist;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class TodayCheckinsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('check_in_date', Carbon::today())
                    ->whereIn('status', ['reserved', 'checked_in'])
                    ->orderBy('check_in_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guest.full_name')
                    ->label('Guest')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('room.roomType.name')
                    ->label('Room Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date()
                    ->label('Check-in')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->label('Check-out')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reserved' => 'warning',
                        'checked_in' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
            ])
            ->heading('Today\'s Check-ins')
            ->defaultSort('check_in_date', 'asc')
            ->paginated([5, 10]);
    }
}

