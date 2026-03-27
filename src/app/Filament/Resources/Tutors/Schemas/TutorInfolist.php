<?php

namespace App\Filament\Resources\Tutors\Schemas;

use App\Models\Tutor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TutorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('ci')
                    ->placeholder('-'),
                TextEntry::make('direccion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('telefono')
                    ->placeholder('-'),
                TextEntry::make('celular')
                    ->placeholder('-'),
                TextEntry::make('genero')
                    ->placeholder('-'),
                TextEntry::make('comentarios')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Tutor $record): bool => $record->trashed()),
            ]);
    }
}
