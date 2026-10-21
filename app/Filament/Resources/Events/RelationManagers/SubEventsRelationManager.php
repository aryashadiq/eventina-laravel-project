<?php

namespace App\Filament\Resources\Events\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class SubEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'subevents';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('start_at')
                    ->label('Start At')
                    ->required(),

                DateTimePicker::make('end_at')
                    ->label('End At'),

                Repeater::make('tickets')
                    ->label('Tickets')
                    ->relationship('tickets')
                    ->schema([
                        Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'IDR' => 'Rp (IDR)',
                            ])
                            ->default('IDR')
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->required(),

                        TextInput::make('tax')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label('Start')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_at')
                    ->label('End')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
