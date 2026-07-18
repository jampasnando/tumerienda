<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function ofertaFecha(Request $request)
    {
        $fecha = $request->fecha;
        $beneficiarioId = $request->beneficiario_id;

        // Oferta de esa fecha
        $oferta = Oferta::where('fecha', $fecha)
            ->where('activo', 1)
            ->first();

        if (!$oferta) {
            return response()->json([
                'grupos' => []
            ]);
        }

        // Menús de la oferta
        $menusOferta = DB::table('menu_oferta')
            ->join('menus', 'menus.id', '=', 'menu_oferta.menu_id')
            ->join('grupos', 'grupos.id', '=', 'menu_oferta.grupo_id')
            ->where('menu_oferta.oferta_id', $oferta->id)
            ->orderBy('grupos.orden', 'asc')
            ->select(
                'menu_oferta.grupo_id',
                'menus.id',
                'menus.nombre',
                'menus.descripcion',
                'menus.foto',
                'menus.precio',
                'grupos.nombre as nombre_grupo',
                'grupos.orden as ordengrupo'
            )
            ->get();
        Log::debug('menusOferta', ['data' => $menusOferta]);
        // Suscripciones existentes
        $suscripciones = DB::table('suscripciones')
            ->where('beneficiario_id', $beneficiarioId)
            ->where('oferta_id', $oferta->id)
            ->pluck('menu_id')
            ->toArray();

        // Agrupar preservando el orden real de los grupos
        $gruposPorNombre = [];

        foreach ($menusOferta as $menu) {
            $nombreGrupo = $menu->nombre_grupo;

            if (!isset($gruposPorNombre[$nombreGrupo])) {
                $gruposPorNombre[$nombreGrupo] = [
                    'nombre' => $nombreGrupo,
                    'ordengrupo' => $menu->ordengrupo,
                    'menus' => []
                ];
            }

            $gruposPorNombre[$nombreGrupo]['menus'][] = [
                'id' => $menu->id,
                'nombre' => $menu->nombre,
                'descripcion' => $menu->descripcion,
                'foto' => $menu->foto,
                'precio' => $menu->precio,
                'seleccionado' => in_array($menu->id, $suscripciones),
                'nombre_grupo' => $menu->nombre_grupo,
                'ordengrupo' => $menu->ordengrupo
            ];
        }

        $grupos = [];

        foreach (collect($gruposPorNombre)->sortBy('ordengrupo','SORT_REGULAR',false)->values() as $grupo) {
            $grupos[$grupo['nombre']] = $grupo['menus'];
        }

        return response()->json([
            'oferta_id' => $oferta->id,
            'grupos' => $grupos
        ]);
    }
}
