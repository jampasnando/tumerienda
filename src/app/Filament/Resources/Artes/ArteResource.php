<?php

namespace App\Filament\Resources\Artes;

use App\Filament\Resources\Artes\Pages\CreateArte;
use App\Filament\Resources\Artes\Pages\EditArte;
use App\Filament\Resources\Artes\Pages\ListArtes;
use App\Filament\Resources\Artes\Schemas\ArteForm;
use App\Filament\Resources\Artes\Tables\ArtesTable;
use App\Models\Arte;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArteResource extends Resource
{
    protected static ?string $model = Arte::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ArteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArtesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArtes::route('/'),
            'create' => CreateArte::route('/create'),
            'edit' => EditArte::route('/{record}/edit'),
        ];
    }
}
