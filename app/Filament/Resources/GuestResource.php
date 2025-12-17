<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestResource\Pages;
use App\Filament\Resources\GuestResource\RelationManagers\BookingsRelationManager;
use App\Models\Guest;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('viewAny', Guest::class) ?? false;
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Hotel Management';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->maxDate(now()->subYears(18)),
                Forms\Components\TextInput::make('id_number')
                    ->label('ID Number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nationality')
                    ->maxLength(255),
                Forms\Components\Select::make('preferred_language')
                    ->label('Preferred Language')
                    ->options([
                        'en' => 'English',
                        'es' => 'Spanish',
                        'fr' => 'French',
                        'de' => 'German',
                        'it' => 'Italian',
                        'pt' => 'Portuguese',
                        'zh' => 'Chinese',
                        'ja' => 'Japanese',
                        'ar' => 'Arabic',
                    ]),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_vip')
                    ->label('VIP Guest')
                    ->helperText('Mark this guest as a VIP for special treatment'),
                Forms\Components\TextInput::make('loyalty_points')
                    ->label('Loyalty Points')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Points are automatically calculated from bookings'),
                Forms\Components\KeyValue::make('preferences')
                    ->label('Guest Preferences')
                    ->keyLabel('Preference')
                    ->valueLabel('Value')
                    ->helperText('Store guest preferences (e.g., Room: High floor, Dietary: Vegetarian)'),
                Forms\Components\Textarea::make('special_requests')
                    ->label('Special Requests')
                    ->maxLength(65535)
                    ->rows(3)
                    ->helperText('Any special requests or requirements'),
                Forms\Components\Textarea::make('notes')
                    ->label('Internal Notes')
                    ->maxLength(65535)
                    ->rows(3)
                    ->helperText('Internal notes about this guest (not visible to guest)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\IconColumn::make('is_vip')
                    ->label('VIP')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('loyalty_points')
                    ->label('Points')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->loyalty_points >= 10000 => 'purple',
                        $record->loyalty_points >= 5000 => 'warning',
                        $record->loyalty_points >= 1000 => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('loyalty_tier')
                    ->label('Tier')
                    ->badge()
                    ->color(fn ($record) => match($record->loyalty_tier) {
                        'Platinum' => 'purple',
                        'Gold' => 'warning',
                        'Silver' => 'info',
                        default => 'gray',
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderBy('loyalty_points', $direction);
                    }),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->counts('bookings')
                    ->label('Bookings')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('last_visit_at')
                    ->label('Last Visit')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\Action::make('toggle_vip')
                    ->label(fn ($record) => $record->is_vip ? 'Remove VIP' : 'Mark as VIP')
                    ->icon(fn ($record) => $record->is_vip ? 'heroicon-o-star' : 'heroicon-o-star')
                    ->color(fn ($record) => $record->is_vip ? 'gray' : 'warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_vip' => !$record->is_vip]);
                    }),
                Actions\EditAction::make(),
                Actions\ViewAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuests::route('/'),
            'create' => Pages\CreateGuest::route('/create'),
            'view' => Pages\ViewGuest::route('/{record}'),
            'edit' => Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
