<?php

namespace App\Filament\Resources\Colegios;

use App\Filament\Resources\Colegios\Pages\CreateColegio;
use App\Filament\Resources\Colegios\Pages\EditColegio;
use App\Filament\Resources\Colegios\Pages\ListColegios;
use App\Filament\Resources\Colegios\Pages\ViewColegio;
use App\Filament\Resources\Colegios\Schemas\ColegioForm;
use App\Filament\Resources\Colegios\Schemas\ColegioInfolist;
use App\Filament\Resources\Colegios\Tables\ColegiosTable;
use App\Models\Colegio;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ColegioResource extends Resource
{
    protected static ?string $model = Colegio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'colegio';

    public static function form(Schema $schema): Schema
    {
        return ColegioForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ColegioInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ColegiosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CursosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListColegios::route('/'),
            'create' => CreateColegio::route('/create'),
            'view' => ViewColegio::route('/{record}'),
            'edit' => EditColegio::route('/{record}/edit'),
        ];
    }
}
