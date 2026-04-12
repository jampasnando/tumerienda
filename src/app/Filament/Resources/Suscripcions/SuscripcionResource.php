<?php

namespace App\Filament\Resources\Suscripcions;

use App\Filament\Resources\Suscripcions\Pages\CreateSuscripcion;
use App\Filament\Resources\Suscripcions\Pages\EditSuscripcion;
use App\Filament\Resources\Suscripcions\Pages\ListSuscripcions;
use App\Filament\Resources\Suscripcions\Pages\ViewSuscripcion;
use App\Filament\Resources\Suscripcions\Schemas\SuscripcionForm;
use App\Filament\Resources\Suscripcions\Schemas\SuscripcionInfolist;
use App\Filament\Resources\Suscripcions\Tables\SuscripcionsTable;
use App\Models\Suscripcion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuscripcionResource extends Resource
{
    protected static ?string $model = Suscripcion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'suscripcion';
    protected static ?string $label = 'Suscripción';
    protected static ?string $pluralLabel = 'Suscripciones';

    public static function form(Schema $schema): Schema
    {
        return SuscripcionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuscripcionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuscripcionsTable::configure($table);
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
            'index' => ListSuscripcions::route('/'),
            'create' => CreateSuscripcion::route('/create'),
            'view' => ViewSuscripcion::route('/{record}'),
            'edit' => EditSuscripcion::route('/{record}/edit'),
        ];
    }
}
