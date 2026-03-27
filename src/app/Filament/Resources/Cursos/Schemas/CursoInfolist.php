<?php

namespace App\Filament\Resources\Cursos\Schemas;

use App\Models\Curso;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CursoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('colegio_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('nivel')
                    ->placeholder('-'),
                TextEntry::make('estado')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Curso $record): bool => $record->trashed()),
            ]);
    }
}
