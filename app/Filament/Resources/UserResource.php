<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function () {
                        $roles = \App\Models\Role::query();
                        // Managers cannot assign admin role
                        if (auth()->user()?->isManager() && !auth()->user()?->isAdmin()) {
                            $roles->where('slug', '!=', \App\Models\Role::ADMIN);
                        }
                        return $roles->pluck('name', 'id');
                    }),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirm Password')
                    ->same('password')
                    ->dehydrated(false)
                    ->required(fn (string $operation) => $operation === 'create')
                    ->visible(fn (string $operation) => in_array($operation, ['create', 'edit'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => match($record->role?->slug) {
                        'admin' => 'danger',
                        'manager' => 'warning',
                        'receptionist' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label('Email Verified')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at')),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->visible(fn ($record) => auth()->user()?->isAdmin() || ($record->role?->slug !== \App\Models\Role::ADMIN)),
                Actions\DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()?->isAdmin() && $record->role?->slug !== \App\Models\Role::ADMIN && $record->id !== auth()->id()),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isAdmin() ?? false),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
