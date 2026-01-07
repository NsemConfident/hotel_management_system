<?php

namespace App\Filament\Widgets\Receptionist;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class TodayCheckoutsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('check_out_date', Carbon::today())
                    ->whereIn('status', ['checked_in', 'checked_out'])
                    ->orderBy('check_out_date', 'asc')
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
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->label('Check-out')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'checked_in' => 'warning',
                        'checked_out' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->label('Total')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('USD')
                    ->label('Paid')
                    ->sortable()
                    ->color(fn ($record) => $record->amount_paid >= $record->total_amount ? 'success' : 'danger'),
            ])
            ->heading('Today\'s Check-outs')
            ->defaultSort('check_out_date', 'asc')
            ->paginated([5, 10]);
    }
}

