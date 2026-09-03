<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaMomentResource\Pages;
use App\Filament\Support\MediaUploads;
use App\Filament\Support\TranslatableFields;
use App\Models\MediaMoment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MediaMomentResource extends Resource
{
    protected static ?string $model = MediaMoment::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Live Moments';

    protected static ?string $modelLabel = 'Live Moment';

    protected static ?string $pluralModelLabel = 'Live Moments';

    protected static ?int $navigationSort = 3;

    public static function getTranslatableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Video')
                ->schema([
                    MediaUploads::video('video_path', 'Vertical video', 'uploads/moments/videos')
                        ->required(),
                    MediaUploads::image('poster_path', 'Poster image (optional)', 'uploads/moments/posters'),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Show on homepage')
                        ->default(true),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('title', 'Caption', $locale),
                ];
            }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Caption')->searchable()->limit(30),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Homepage'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->since(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaMoments::route('/'),
            'create' => Pages\CreateMediaMoment::route('/create'),
            'edit' => Pages\EditMediaMoment::route('/{record}/edit'),
        ];
    }
}
