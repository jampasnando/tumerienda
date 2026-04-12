<?php

namespace App\Filament\Resources\Suscripcions\Schemas;

use App\Models\Suscripcion;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SuscripcionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('beneficiario_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('oferta_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('menu_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fecha')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('colegio_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tutor_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Suscripcion $record): bool => $record->trashed()),
                TextEntry::make('estado')
                    ->placeholder('-'),
            ]);
    }
}
