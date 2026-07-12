<?php

namespace App\Filament\Resources\Notificaciones;

use App\Filament\Resources\Notificaciones\Pages\CreateNotificaciones;
use App\Filament\Resources\Notificaciones\Pages\EditNotificaciones;
use App\Filament\Resources\Notificaciones\Pages\ListNotificaciones;
use App\Filament\Resources\Notificaciones\Schemas\NotificacionesForm;
use App\Filament\Resources\Notificaciones\Tables\NotificacionesTable;
use App\Models\Notificacion;
use App\Models\Notificaciones;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificacionesResource extends Resource
{
    protected static ?string $model = Notificacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'donde';

    public static function form(Schema $schema): Schema
    {
        return NotificacionesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificacionesTable::configure($table);
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
            'index' => ListNotificaciones::route('/'),
            'create' => CreateNotificaciones::route('/create'),
            'edit' => EditNotificaciones::route('/{record}/edit'),
        ];
    }
}
