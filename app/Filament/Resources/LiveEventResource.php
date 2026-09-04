<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveEventResource\Pages;
use App\Filament\Support\MediaUploads;
use App\Filament\Support\TranslatableFields;
use App\Models\LiveEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LiveEventResource extends Resource
{
    protected static ?string $model = LiveEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    protected static ?int $navigationSort = 2;

    public static function getTranslatableAttributes(): array
    {
        return ['title', 'description', 'ticket_info'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Event')
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Unique URL key, e.g. everyone-rocks-2026'),
                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('Date & time')
                        ->required()
                        ->seconds(false)
                        ->native(false),
                    Forms\Components\TextInput::make('venue_name')
                        ->label('Venue')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('venue_address')
                        ->label('Address')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('city')
                        ->maxLength(120),
                    MediaUploads::image('poster_path', 'Poster', 'uploads/events/posters'),
                    Forms\Components\TextInput::make('info_url')
                        ->label('More info URL')
                        ->url()
                        ->maxLength(500),
                    Forms\Components\TextInput::make('ticket_url')
                        ->label('Tickets URL')
                        ->url()
                        ->maxLength(500),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Show on homepage')
                        ->default(true),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('title', 'Title', $locale, required: true),
                    TranslatableFields::textarea('description', 'Short description', $locale, 4),
                    TranslatableFields::text('ticket_info', 'Ticket info', $locale),
                ];
            }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('starts_at')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('venue_name')->label('Venue'),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Homepage'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('starts_at')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiveEvents::route('/'),
            'create' => Pages\CreateLiveEvent::route('/create'),
            'edit' => Pages\EditLiveEvent::route('/{record}/edit'),
        ];
    }
}
