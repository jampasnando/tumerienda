<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Oferta $oferta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Oferta $oferta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Oferta $oferta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Oferta $oferta)
    {
        //
    }
    public function activas()
    {
        $hoy = Carbon::today();

        $ofertas = Oferta::with([
                'menus' => function ($q) {
                    $q->where('activo', 1);
                }
            ])
            // ->whereDate('fecha_inicio', '<=', $hoy)
            // ->whereDate('fecha_fin', '>=', $hoy)
            ->get();

        return response()->json(
            $ofertas->map(function ($oferta) {

                return [
                    'id' => $oferta->id,
                    'nombre' => $oferta->nombre,
                    'fecha_inicio' => $oferta->fecha_inicio,
                    'fecha_fin' => $oferta->fecha_fin,

                    // 🔥 clave para tu calendario
                    'dias' => $this->dias($oferta),

                    'menus' => $oferta->menus->map(function ($menu) {
                        return [
                            'id' => $menu->id,
                            'nombre' => $menu->nombre,
                            'descripcion' => $menu->descripcion,
                            'precio' => $menu->precio,
                            'foto' => $menu->foto
                                ? asset('storage/' . $menu->foto)
                                : null,
                        ];
                    }),
                ];
            })
        );
    }

    private function dias($oferta)
    {
        $dias = [];

        $inicio = \Carbon\Carbon::parse($oferta->fecha_inicio);
        $fin = \Carbon\Carbon::parse($oferta->fecha_fin);

        while ($inicio->lte($fin)) {

            if ($inicio->isWeekday()) {
                $dias[] = $inicio->format('Y-m-d');
            }

            $inicio->addDay();
        }

        return $dias;
    }
}
