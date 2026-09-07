<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuSectionResource\Pages;
use App\Filament\Resources\MenuSectionResource\RelationManagers;
use App\Filament\Support\TranslatableFields;
use App\Models\MenuSection;
use App\Support\SiteMenus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuSectionResource extends Resource
{
    protected static ?string $model = MenuSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $navigationGroup = 'Navigation';

    protected static ?string $navigationLabel = 'Menu sections';

    protected static ?string $modelLabel = 'Menu section';

    protected static ?string $pluralModelLabel = 'Menu sections';

    protected static ?int $navigationSort = 1;

    public static function getTranslatableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Placement')
                ->schema([
                    Forms\Components\Select::make('location')
                        ->options([
                            'header' => 'Header (top menu)',
                            'footer' => 'Footer column',
                        ])
                        ->required()
                        ->native(false),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\Toggle::make('show_title')
                        ->label('Show section title on site')
                        ->helperText('Usually off for the header menu, on for footer columns.')
                        ->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('title', 'Section title', $locale),
                ];
            }, 'Title'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'header' ? 'Header' : 'Footer')
                    ->color(fn (string $state): string => $state === 'header' ? 'info' : 'success'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('items_count')->counts('items')->label('Items'),
                Tables\Columns\IconColumn::make('show_title')->boolean()->label('Show title'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('location')->options([
                    'header' => 'Header',
                    'footer' => 'Footer',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuSections::route('/'),
            'create' => Pages\CreateMenuSection::route('/create'),
            'edit' => Pages\EditMenuSection::route('/{record}/edit'),
        ];
    }

    public static function afterSave(): void
    {
        SiteMenus::forgetCache();
    }
}
