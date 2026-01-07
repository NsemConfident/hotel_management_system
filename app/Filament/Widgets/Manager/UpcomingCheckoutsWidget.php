<?php

namespace App\Filament\Widgets\Manager;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class UpcomingCheckoutsWidget extends BaseWidget
{
    protected static ?int $sort = 8;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->where('status', 'checked_in')
                    ->where('check_out_date', '>=', Carbon::today())
                    ->where('check_out_date', '<=', Carbon::today()->addDays(3))
                    ->orderBy('check_out_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('guest.full_name')
                    ->label('Guest')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->label('Check-out Date')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->check_out_date->isToday() => 'danger',
                        $record->check_out_date->isTomorrow() => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->label('Total Amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('USD')
                    ->label('Paid')
                    ->sortable()
                    ->color(fn ($record) => $record->amount_paid >= $record->total_amount ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money('USD')
                    ->getStateUsing(fn ($record) => $record->total_amount - $record->amount_paid)
                    ->color('danger')
                    ->sortable(),
            ])
            ->heading('Upcoming Check-outs (Next 3 Days)')
            ->defaultSort('check_out_date', 'asc')
            ->paginated([5, 10, 15]);
    }
}

