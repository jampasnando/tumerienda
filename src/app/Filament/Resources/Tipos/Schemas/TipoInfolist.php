<?php

namespace App\Filament\Resources\Tipos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TipoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tipo'),
            ]);
    }
}
