<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BandMemberResource\Pages;
use App\Filament\Support\MediaUploads;
use App\Filament\Support\TranslatableFields;
use App\Models\BandMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BandMemberResource extends Resource
{
    protected static ?string $model = BandMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function getTranslatableAttributes(): array
    {
        return ['role', 'bio'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General')
                ->schema([
                    Forms\Components\TextInput::make('name')->maxLength(255),
                    MediaUploads::image('image', 'Photo', 'uploads/band'),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),

            TranslatableFields::tabs(function (string $locale) {
                return [
                    TranslatableFields::text('role', 'Role', $locale, required: true),
                    TranslatableFields::textarea('bio', 'Bio', $locale, 3)->columnSpanFull(),
                ];
            }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
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
            'index' => Pages\ListBandMembers::route('/'),
            'create' => Pages\CreateBandMember::route('/create'),
            'edit' => Pages\EditBandMember::route('/{record}/edit'),
        ];
    }
}
