<?php

namespace App\Filament\Resources\Beneficiarios\Schemas;

use App\Models\Beneficiario;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BeneficiarioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre')
                    ->placeholder('-'),
                TextEntry::make('fechanac')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('genero')
                    ->placeholder('-'),
                TextEntry::make('codigo')
                    ->placeholder('-'),
                TextEntry::make('comentarios')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Beneficiario $record): bool => $record->trashed()),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
