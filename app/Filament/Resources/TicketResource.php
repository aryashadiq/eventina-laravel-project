<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Tickets';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Tickets';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Information')
                    ->description('Details about the event this ticket is for')
                    ->schema([
                        TextInput::make('subevent.event.title')
                            ->label('Event')
                            ->disabled()
                            ->columnSpanFull(),
                        TextInput::make('subevent.title')
                            ->label('Sub-Event / Session')
                            ->disabled()
                            ->columnSpanFull(),
                        TextInput::make('subevent.event.organizer.name')
                            ->label('Organizer')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Ticket Details')
                    ->description('Ticket code, price, and currency information')
                    ->schema([
                        TextInput::make('code')
                            ->label('Ticket Code')
                            ->disabled()
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->required(),
                        TextInput::make('currency')
                            ->label('Currency')
                            ->maxLength(3),
                        TextInput::make('tax')
                            ->label('Tax Amount')
                            ->numeric(),
                    ])
                    ->columns(2),

                Section::make('User Information')
                    ->description('Person who owns this ticket')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('invoices.user', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Ticket Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subevent.title')
                    ->label('Session (Date/Time)')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $state ?? 'N/A'),
                Tables\Columns\TextColumn::make('subevent.event.title')
                    ->label('Event Name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $state ?? 'N/A'),
                Tables\Columns\TextColumn::make('invoices_count')
                    ->label('Sold')
                    ->counts('invoices')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn($state, $record) => $record->currency . ' ' . number_format($state, 0, ',', '.')),
                Tables\Columns\BadgeColumn::make('currency')
                    ->label('Currency'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('subevent')
                    ->relationship('subevent', 'title')
                    ->label('Session'),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    protected static function getQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getQuery()->with(['subevent.event', 'invoices.user']);
    }
}
