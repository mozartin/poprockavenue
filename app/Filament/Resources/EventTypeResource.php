<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventTypeResource\Pages;
use App\Filament\Support\TranslatableFields;
use App\Models\EventType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EventTypeResource extends Resource
{
    protected static ?string $model = EventType::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function getTranslatableAttributes(): array
    {
        return [
            'name',
            'title',
            'subtitle',
            'description',
            'content',
            'meta_title',
            'meta_description',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General')
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Same URL slug for all languages, e.g. weddings'),
                    Forms\Components\TextInput::make('image')->label('Card Image Path/URL'),
                    Forms\Components\TextInput::make('hero_image')->label('Hero Image Path/URL'),
                    Forms\Components\ColorPicker::make('accent_color')->default('#22D3EE'),
                    Forms\Components\Toggle::make('is_featured')->default(true),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('name', 'Name', $locale, required: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Set $set, ?string $state) use ($locale) {
                            if ($locale === 'en' && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    TranslatableFields::text('title', 'Title', $locale, required: true),
                    TranslatableFields::text('subtitle', 'Subtitle', $locale),
                    TranslatableFields::textarea('description', 'Short Description', $locale, 2)
                        ->columnSpanFull(),
                    TranslatableFields::richEditor('content', 'Page Content', $locale),
                    TranslatableFields::text('meta_title', 'SEO Title', $locale),
                    TranslatableFields::textarea('meta_description', 'SEO Description', $locale, 2)
                        ->columnSpanFull(),
                ];
            }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventTypes::route('/'),
            'create' => Pages\CreateEventType::route('/create'),
            'edit' => Pages\EditEventType::route('/{record}/edit'),
        ];
    }
}
