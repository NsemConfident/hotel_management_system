<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentsRelationManager;
use App\Models\Booking;
use App\Models\Room;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Booking::class) ?? false;
    }

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Hotel Management';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Select::make('guest_id')
                            ->relationship('guest', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}" . ($record->email ? " ({$record->email})" : ''))
                            ->required()
                            ->searchable(['first_name', 'last_name', 'email', 'phone'])
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('id_number')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('address'),
                            ]),
                        Forms\Components\Select::make('room_id')
                            ->relationship('room', 'room_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->room_number} - {$record->roomType->name} (Floor {$record->floor}) - " . ucfirst($record->status))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state) {
                                    $room = Room::find($state);
                                    if ($room && $room->roomType) {
                                        $checkIn = $get('check_in_date');
                                        $checkOut = $get('check_out_date');
                                        if ($checkIn && $checkOut) {
                                            $days = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
                                            if ($days > 0) {
                                                $set('total_amount', $room->roomType->base_price * $days);
                                            }
                                        }
                                    }
                                }
                            }),
                        Forms\Components\DatePicker::make('check_in_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y-m-d')
                            ->minDate(now())
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $checkOut = $get('check_out_date');
                                if ($state && $checkOut) {
                                    $checkOutDate = \Carbon\Carbon::parse($checkOut);
                                    $checkInDate = \Carbon\Carbon::parse($state);
                                    if ($checkOutDate->lte($checkInDate)) {
                                        $set('check_out_date', $checkInDate->copy()->addDay());
                                    }
                                }
                                // Recalculate total
                                $roomId = $get('room_id');
                                if ($roomId && $checkOut) {
                                    $room = Room::find($roomId);
                                    if ($room && $room->roomType) {
                                        $days = \Carbon\Carbon::parse($state)->diffInDays(\Carbon\Carbon::parse($checkOut));
                                        if ($days > 0) {
                                            $set('total_amount', $room->roomType->base_price * $days);
                                        }
                                    }
                                }
                            }),
                        Forms\Components\DatePicker::make('check_out_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y-m-d')
                            ->minDate(fn (Forms\Get $get) => $get('check_in_date') ? \Carbon\Carbon::parse($get('check_in_date'))->addDay() : now()->addDay())
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                // Recalculate total
                                $roomId = $get('room_id');
                                $checkIn = $get('check_in_date');
                                if ($roomId && $checkIn && $state) {
                                    $room = Room::find($roomId);
                                    if ($room && $room->roomType) {
                                        $days = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($state));
                                        if ($days > 0) {
                                            $set('total_amount', $room->roomType->base_price * $days);
                                        }
                                    }
                                }
                            }),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Status & Payment')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'reserved' => 'Reserved',
                                'checked_in' => 'Checked In',
                                'checked_out' => 'Checked Out',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('reserved'),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->required()
                            ->default(0),
                        Forms\Components\TextInput::make('amount_paid')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking #')
                    ->sortable(),
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
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
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
                Tables\Filters\Filter::make('check_in_date')
                    ->form([
                        Forms\Components\DatePicker::make('check_in_from')
                            ->label('Check-in From'),
                        Forms\Components\DatePicker::make('check_in_until')
                            ->label('Check-in Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['check_in_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('check_in_date', '>=', $date),
                            )
                            ->when(
                                $data['check_in_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('check_in_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Actions\Action::make('check_in')
                    ->label('Check In')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'reserved' && auth()->user()?->can('checkIn', $record))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'checked_in',
                        ]);
                        $record->room->update([
                            'status' => 'occupied',
                        ]);
                        // Update guest's last visit
                        $record->guest->update(['last_visit_at' => now()]);
                    }),
                Actions\Action::make('check_out')
                    ->label('Check Out')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'checked_in' && auth()->user()?->can('checkOut', $record))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'checked_out',
                        ]);
                        
                        // Check if room has other active bookings
                        $hasActiveBooking = Booking::where('room_id', $record->room_id)
                            ->where('id', '!=', $record->id)
                            ->whereIn('status', ['reserved', 'checked_in'])
                            ->exists();

                        if (!$hasActiveBooking) {
                            $record->room->update(['status' => 'available']);
                        }

                        // Award loyalty points
                        $guest = $record->guest;
                        $points = $guest->calculateLoyaltyPointsFromBooking($record);
                        $guest->addLoyaltyPoints($points, "Booking #{$record->id} completed");
                    }),
                Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['reserved', 'checked_in']) && auth()->user()?->can('cancel', $record))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'cancelled',
                        ]);
                        if ($record->status === 'checked_in') {
                            $record->room->update([
                                'status' => 'available',
                            ]);
                        }
                    }),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('check_in_date', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
