<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerResource\Pages;
use App\Models\Volunteer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Volunteers';

    protected static ?string $modelLabel = 'Volunteer';

    protected static ?string $pluralModelLabel = 'Volunteers';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Volunteer\'s basic contact details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make('Background & Availability')
                    ->description('Experience, motivation, and availability')
                    ->schema([
                        Textarea::make('experience')
                            ->label('Previous Volunteer Experience')
                            ->placeholder('Share any previous volunteer work or relevant experience...')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('motivation')
                            ->label('Why Do You Want to Volunteer?')
                            ->placeholder('Tell us what motivates you to volunteer for this event...')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('availability')
                            ->label('Time Availability')
                            ->options([
                                'full-day' => '⏰ Full Day',
                                'morning' => '🌅 Morning Only',
                                'afternoon' => '☀️ Afternoon Only',
                                'evening' => '🌙 Evening Only',
                                'flexible' => '🔄 Flexible',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Application Status')
                            ->options([
                                'pending' => '⏳ Pending',
                                'approved' => '✅ Approved',
                                'rejected' => '❌ Rejected',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('availability')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }
}
