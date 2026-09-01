<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepertoireCategoryResource\Pages;
use App\Filament\Resources\RepertoireCategoryResource\RelationManagers\SongsRelationManager;
use App\Filament\Support\TranslatableFields;
use App\Models\RepertoireCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RepertoireCategoryResource extends Resource
{
    protected static ?string $model = RepertoireCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    public static function getTranslatableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General')
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\ColorPicker::make('accent_color')->default('#22D3EE'),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('name', 'Category Name', $locale, required: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Set $set, ?string $state) use ($locale) {
                            if ($locale === 'en' && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        }),
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
                Tables\Columns\TextColumn::make('songs_count')->counts('songs'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [SongsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepertoireCategories::route('/'),
            'create' => Pages\CreateRepertoireCategory::route('/create'),
            'edit' => Pages\EditRepertoireCategory::route('/{record}/edit'),
        ];
    }
}
