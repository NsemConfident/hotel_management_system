<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\BookingPayment;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->minValue(0.01),
                Forms\Components\Select::make('method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'transfer' => 'Bank Transfer',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->default('cash'),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->required()
                    ->default(now())
                    ->native(false)
                    ->displayFormat('Y-m-d H:i'),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255)
                    ->placeholder('Transaction reference or receipt number'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'transfer' => 'warning',
                        'other' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['booking_id'] = $this->ownerRecord->id;
                        return $data;
                    })
                    ->after(function () {
                        // Update booking amount_paid
                        $this->ownerRecord->refresh();
                        $totalPaid = $this->ownerRecord->payments()->sum('amount');
                        $this->ownerRecord->update(['amount_paid' => $totalPaid]);
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->after(function () {
                        // Update booking amount_paid
                        $this->ownerRecord->refresh();
                        $totalPaid = $this->ownerRecord->payments()->sum('amount');
                        $this->ownerRecord->update(['amount_paid' => $totalPaid]);
                    }),
                Actions\DeleteAction::make()
                    ->after(function () {
                        // Update booking amount_paid
                        $this->ownerRecord->refresh();
                        $totalPaid = $this->ownerRecord->payments()->sum('amount');
                        $this->ownerRecord->update(['amount_paid' => $totalPaid]);
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->after(function () {
                            // Update booking amount_paid
                            $this->ownerRecord->refresh();
                            $totalPaid = $this->ownerRecord->payments()->sum('amount');
                            $this->ownerRecord->update(['amount_paid' => $totalPaid]);
                        }),
                ]),
            ]);
    }
}
