<?php

namespace App\Filament\Resources\Grupos\Schemas;

use App\Models\Grupo;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GrupoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Grupo $record): bool => $record->trashed()),
            ]);
    }
}
