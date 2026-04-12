<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Models\Menu;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RichTextEntry;
use Filament\Schemas\Schema;

class MenuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('descripcion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('costo')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('precio')
                    ->numeric()
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
                    ->visible(fn (Menu $record): bool => $record->trashed()),
                TextEntry::make('foto')
                    ->placeholder('-'),
                TextEntry::make('ingredientes')
                    ->markdown()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('preparacion')
                    ->placeholder('-')
                    ->markdown()
                    ->columnSpanFull(),
            ]);
    }
}
