<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->maxLength(255)
                    ->hint('Leave empty to auto-generate from title'),

                FileUpload::make('thumbnail')
                    ->image()
                    ->visibility('public')
                    ->directory('events/thumbnails')
                    ->imagePreviewHeight('150')
                    ->panelAspectRatio('16:9'),

                Section::make('Venue')
                    ->schema([
                        TextInput::make('venue.name')
                            ->label('Venue Name')
                            ->maxLength(255),

                        TextInput::make('venue.address')
                            ->label('Address')
                            ->maxLength(1000),
                    ])
                    ->columns(1),

                Textarea::make('description')
                    ->columnSpanFull(),

                Select::make('organizer_id')
                    ->label('Organizer')
                    ->relationship('organizer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),
            ]);
    }
}
