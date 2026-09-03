<?php

namespace App\Filament\Resources;

use App\Enums\BookingStatus;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                        Forms\Components\TextInput::make('phone')->tel()->maxLength(50),
                    ])->columns(3),

                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('event_type')->required()->maxLength(100),
                        Forms\Components\DatePicker::make('event_date'),
                        Forms\Components\TextInput::make('location')->maxLength(255),
                        Forms\Components\TextInput::make('guests')->numeric()->minValue(1),
                        Forms\Components\Textarea::make('message')->rows(4)->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Internal')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(collect(BookingStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                            ->required()
                            ->default(BookingStatus::New->value),
                        Forms\Components\Textarea::make('notes')->rows(4)->columnSpanFull(),
                        Forms\Components\TextInput::make('ip_address')->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('event_type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('event_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('location')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (BookingStatus $state): string => $state->color())
                    ->formatStateUsing(fn (BookingStatus $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(BookingStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
                Tables\Filters\SelectFilter::make('event_type')
                    ->options(fn () => Booking::query()->distinct()->pluck('event_type', 'event_type')->all()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
