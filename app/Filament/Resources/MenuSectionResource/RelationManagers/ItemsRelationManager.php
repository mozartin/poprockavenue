<?php

namespace App\Filament\Resources\MenuSectionResource\RelationManagers;

use App\Filament\Support\MenuLinkOptions;
use App\Filament\Support\TranslatableFields;
use App\Support\SiteMenus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Menu items';

    public function form(Form $form): Form
    {
        return $form->schema([
            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('label', 'Label', $locale, required: true),
                ];
            }, 'Label'),

            Forms\Components\Select::make('link_type')
                ->label('Link type')
                ->options([
                    'route' => 'Internal page',
                    'url' => 'Custom URL',
                ])
                ->default('route')
                ->required()
                ->live()
                ->native(false),

            Forms\Components\Select::make('link_route')
                ->label('Page')
                ->options(MenuLinkOptions::routes())
                ->searchable()
                ->native(false)
                ->required(fn (Get $get): bool => $get('link_type') === 'route')
                ->visible(fn (Get $get): bool => $get('link_type') === 'route'),

            Forms\Components\TextInput::make('link_url')
                ->label('URL')
                ->url()
                ->maxLength(500)
                ->required(fn (Get $get): bool => $get('link_type') === 'url')
                ->visible(fn (Get $get): bool => $get('link_type') === 'url'),

            Forms\Components\TextInput::make('anchor')
                ->label('Anchor (optional)')
                ->placeholder('#events')
                ->helperText('Appended to the link, e.g. #services')
                ->maxLength(100),

            Forms\Components\Toggle::make('open_in_new_tab')->label('Open in new tab'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('link_type')->badge(),
                Tables\Columns\TextColumn::make('link_value'),
                Tables\Columns\TextColumn::make('anchor'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $this->prepareItemData($data))
                    ->after(fn () => SiteMenus::forgetCache()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(fn (array $data): array => $this->expandItemData($data))
                    ->mutateFormDataUsing(fn (array $data): array => $this->prepareItemData($data))
                    ->after(fn () => SiteMenus::forgetCache()),
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => SiteMenus::forgetCache()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(fn () => SiteMenus::forgetCache()),
                ]),
            ]);
    }

    protected function expandItemData(array $data): array
    {
        $record = $this->getMountedTableActionRecord();

        if ($record) {
            foreach (TranslatableFields::locales() as $locale) {
                $data[TranslatableFields::key('label', $locale)] = $record->getTranslation('label', $locale, false);
            }

            $data['link_type'] = $record->link_type;
            $data['link_route'] = $record->link_type === 'route' ? $record->link_value : null;
            $data['link_url'] = $record->link_type === 'url' ? $record->link_value : null;
        }

        return $data;
    }

    protected function prepareItemData(array $data): array
    {
        $translations = [];

        foreach (TranslatableFields::locales() as $locale) {
            $key = TranslatableFields::key('label', $locale);

            if (array_key_exists($key, $data)) {
                $translations[$locale] = $data[$key] ?? '';
                unset($data[$key]);
            }
        }

        if ($translations !== []) {
            $data['label'] = $translations;
        }

        $type = $data['link_type'] ?? 'route';
        $data['link_value'] = $type === 'url'
            ? (string) ($data['link_url'] ?? '')
            : (string) ($data['link_route'] ?? 'home');

        unset($data['link_route'], $data['link_url']);

        return $data;
    }
}
