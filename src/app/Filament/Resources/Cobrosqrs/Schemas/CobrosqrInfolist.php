<?php

namespace App\Filament\Resources\Cobrosqrs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CobrosqrInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->numeric(),
                TextEntry::make('alias'),
                TextEntry::make('numeroOrdenOriginante'),
                TextEntry::make('monto')
                    ->numeric(),
                TextEntry::make('idQr'),
                TextEntry::make('moneda'),
                TextEntry::make('fechaproceso'),
                TextEntry::make('cuentaCliente'),
                TextEntry::make('nombreCliente'),
                TextEntry::make('documentoCliente'),
                TextEntry::make('fechareg')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
