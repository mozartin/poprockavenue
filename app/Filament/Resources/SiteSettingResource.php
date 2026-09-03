<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Support\TranslatableFields;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\Select::make('type')
                ->options([
                    'text' => 'Text (single language)',
                    'translatable' => 'Text (EN / NL / UA)',
                    'boolean' => 'Boolean',
                    'integer' => 'Integer',
                    'json' => 'JSON',
                ])
                ->default('text')
                ->required()
                ->live(),
            Forms\Components\Select::make('group')->options([
                'general' => 'General',
                'contact' => 'Contact',
                'social' => 'Social',
                'media' => 'Media',
                'content' => 'Content',
                'copy' => 'Website Texts',
                'legal' => 'Legal',
            ])->default('general')->required(),

            Forms\Components\Textarea::make('value')
                ->rows(3)
                ->columnSpanFull()
                ->visible(fn (Get $get): bool => $get('type') !== 'translatable'),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::textarea('value', 'Content', $locale, 4)->columnSpanFull(),
                ];
            })->visible(fn (Get $get): bool => $get('type') === 'translatable'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value')->limit(50)->searchable(),
                Tables\Columns\TextColumn::make('group')->badge()->sortable(),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')->options([
                    'general' => 'General',
                    'contact' => 'Contact',
                    'social' => 'Social',
                    'media' => 'Media',
                    'content' => 'Content',
                    'legal' => 'Legal',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
