<?php

namespace App\Filament\Resources\Suscripcions\Schemas;

use App\Models\Oferta;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;

class SuscripcionForm
{
    public static function generarDias($ofertaId, $set)
    {
        $oferta = Oferta::find($ofertaId);

        if (!$oferta) return;

        $inicio = Carbon::parse($oferta->fecha_inicio);
        $fin = Carbon::parse($oferta->fecha_fin);

        $dias = [];

        while ($inicio->lte($fin)) {

            if (!$inicio->isSunday()) {
                $dias[] = [
                    'fecha' => $inicio->format('Y-m-d'),
                    'menu_id' => null,
                ];
            }

            $inicio->addDay();
        }

        $set('dias', $dias);
    }
    public static function menusPorOferta($ofertaId)
    {
        return Oferta::find($ofertaId)
            ?->menus()
            ->get()
            ->mapWithKeys(function ($menu) {
                $precio = number_format($menu->precio, 2);
                return [
                    $menu->id => "🍱 {$menu->nombre} — Bs {$precio}"
                ];
            })
            ->toArray() ?? [];
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('beneficiario_id')
                    ->relationship('beneficiario', 'nombre')
                    ->required(),

                Select::make('oferta_id')
                    ->relationship('oferta', 'nombre')
                    ->reactive()
                    // ->afterStateUpdated(fn ($state, $set) =>
                    //     self::generarDias($state, $set)
                    // )
                    ->afterStateUpdated(fn ($set) => $set('calendario', []))
                    ->required(),
                ViewField::make('calendario')
                    ->view('filament.forms.components.calendario-menus')
                    ->reactive()
                    ->dehydrated(true)
                    ->columnSpanFull()
                    ->required(),
                // Repeater::make('dias')
                //     ->schema([
                //         DatePicker::make('fecha')
                //             ->disabled(),

                //         ViewField::make('menu_id')
                //             ->view('filament.forms.components.menu-cards')
                //             ->reactive()
                //             ->dehydrated(true)
                //             ->required(),
                //     ])
                //     ->addable(false)
                //     ->deletable(false),
            ]);
    }
}
