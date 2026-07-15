<?php

namespace App\Filament\Resources\Notificaciones\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificacionesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('notificaciones'),
                Select::make('donde')
                    ->options([
                        'inicio'=>'Inicio',
                        'calendario'=>'Calendario',
                        'hijos' => 'Hijos',
                        'sinplanes'=> 'Sin plan',
                        'qrpagado' => 'QR pagado'
                    ]),
                FileUpload::make('logo')
                    ->label('logo/imagen')
                    ->disk('public')
                    ->directory('artes')
                    ->image()
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
                ColorPicker::make('color'),
                Toggle::make('estado')
            ]);
    }
}
