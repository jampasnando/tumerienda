<?php

namespace App\Filament\Resources\Colegios\Schemas;

use App\Models\Colegio;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ColegioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('direccion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('telefono')
                    ->placeholder('-'),
                TextEntry::make('celular')
                    ->placeholder('-'),
                TextEntry::make('contacto')
                    ->placeholder('-'),
                TextEntry::make('latitud')
                    ->placeholder('-'),
                TextEntry::make('longitud')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Colegio $record): bool => $record->trashed()),
            ]);
    }
}
