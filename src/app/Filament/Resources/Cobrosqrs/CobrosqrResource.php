<?php

namespace App\Filament\Resources\Cobrosqrs;

use App\Filament\Resources\Cobrosqrs\Pages\CreateCobrosqr;
use App\Filament\Resources\Cobrosqrs\Pages\EditCobrosqr;
use App\Filament\Resources\Cobrosqrs\Pages\ListCobrosqrs;
use App\Filament\Resources\Cobrosqrs\Pages\ViewCobrosqr;
use App\Filament\Resources\Cobrosqrs\Schemas\CobrosqrForm;
use App\Filament\Resources\Cobrosqrs\Schemas\CobrosqrInfolist;
use App\Filament\Resources\Cobrosqrs\Tables\CobrosqrsTable;
use App\Models\Cobrosqr;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CobrosqrResource extends Resource
{
    protected static ?string $model = Cobrosqr::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'alias';

    public static function form(Schema $schema): Schema
    {
        return CobrosqrForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CobrosqrInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CobrosqrsTable::configure($table);
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
            'index' => ListCobrosqrs::route('/'),
            'create' => CreateCobrosqr::route('/create'),
            'view' => ViewCobrosqr::route('/{record}'),
            'edit' => EditCobrosqr::route('/{record}/edit'),
        ];
    }
}
