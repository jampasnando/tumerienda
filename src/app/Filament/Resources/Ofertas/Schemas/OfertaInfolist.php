<?php

namespace App\Filament\Resources\Ofertas\Schemas;

use App\Models\Oferta;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OfertaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('fecha_inicio')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fecha_fin')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('activo')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Oferta $record): bool => $record->trashed()),
            ]);
    }
}
