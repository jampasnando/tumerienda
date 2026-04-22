<?php

namespace App\Filament\Resources\Tipos;

use App\Filament\Resources\Tipos\Pages\CreateTipo;
use App\Filament\Resources\Tipos\Pages\EditTipo;
use App\Filament\Resources\Tipos\Pages\ListTipos;
use App\Filament\Resources\Tipos\Pages\ViewTipo;
use App\Filament\Resources\Tipos\Schemas\TipoForm;
use App\Filament\Resources\Tipos\Schemas\TipoInfolist;
use App\Filament\Resources\Tipos\Tables\TiposTable;
use App\Models\Tipo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoResource extends Resource
{
    protected static ?string $model = Tipo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'tipo';

    public static function form(Schema $schema): Schema
    {
        return TipoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TipoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiposTable::configure($table);
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
            'index' => ListTipos::route('/'),
            'create' => CreateTipo::route('/create'),
            'view' => ViewTipo::route('/{record}'),
            'edit' => EditTipo::route('/{record}/edit'),
        ];
    }
}
