<?php

namespace App\Filament\Resources\Envases;

use App\Filament\Resources\Envases\Pages\CreateEnvase;
use App\Filament\Resources\Envases\Pages\EditEnvase;
use App\Filament\Resources\Envases\Pages\ListEnvases;
use App\Filament\Resources\Envases\Pages\ViewEnvase;
use App\Filament\Resources\Envases\Schemas\EnvaseForm;
use App\Filament\Resources\Envases\Schemas\EnvaseInfolist;
use App\Filament\Resources\Envases\Tables\EnvasesTable;
use App\Models\Envase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EnvaseResource extends Resource
{
    protected static ?string $model = Envase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return EnvaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EnvaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnvasesTable::configure($table);
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
            'index' => ListEnvases::route('/'),
            'create' => CreateEnvase::route('/create'),
            'view' => ViewEnvase::route('/{record}'),
            'edit' => EditEnvase::route('/{record}/edit'),
        ];
    }
}
