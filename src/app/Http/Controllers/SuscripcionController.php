<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    /**
     * 🔹 LISTAR suscripciones (para precargar calendario)
     * GET /api/suscripciones?beneficiario_id=1&oferta_id=1
     */
    public function index(Request $request)
    {
        $request->validate([
            'beneficiario_id' => 'required|integer',
            'oferta_id' => 'required|integer',
        ]);

        $suscripciones = Suscripcion::where('beneficiario_id', $request->beneficiario_id)
            ->where('oferta_id', $request->oferta_id)
            ->whereNull('deleted_at')
            ->get();

        // 🔥 devolver formato: fecha => menu_id
        $resultado = [];

        foreach ($suscripciones as $s) {
            $resultado[$s->fecha] = $s->menu_id;
        }

        return response()->json($resultado);
    }

    /**
     * 🔹 GUARDAR / ACTUALIZAR suscripciones masivas
     * POST /api/suscripciones
     */
    public function store(Request $request)
    {
        $request->validate([
            'beneficiario_id' => 'required|integer',
            'oferta_id' => 'required|integer',
            'colegio_id' => 'required|integer',
            'selecciones' => 'required|array',
        ]);

        $oferta = Oferta::with('menus')->findOrFail($request->oferta_id);

        $tutor_id = $request->user()->id;

        // 🔥 obtener IDs válidos de menús de esta oferta
        $menusValidos = $oferta->menus->pluck('id')->toArray();

        foreach ($request->selecciones as $fecha => $menu_id) {

            $fechaCarbon = Carbon::parse($fecha);

            // ✅ validar rango de fechas
            if (
                $fechaCarbon->lt($oferta->fecha_inicio) ||
                $fechaCarbon->gt($oferta->fecha_fin)
            ) {
                continue;
            }

            // ✅ solo lunes a viernes
            if (!$fechaCarbon->isWeekday()) {
                continue;
            }

            // ✅ validar menú pertenece a la oferta
            if (!in_array($menu_id, $menusValidos)) {
                continue;
            }

            // 🔥 guardar (update o create)
            Suscripcion::updateOrCreate(
                [
                    'beneficiario_id' => $request->beneficiario_id,
                    'fecha' => $fecha,
                ],
                [
                    'oferta_id' => $request->oferta_id,
                    'menu_id' => $menu_id,
                    'colegio_id' => $request->colegio_id,
                    'tutor_id' => $tutor_id,
                    'estado' => 'activo',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Suscripciones guardadas correctamente'
        ]);
    }

    /**
     * 🔥 OPCIONAL: eliminar suscripciones de una oferta
     */
    public function destroyPorOferta(Request $request)
    {
        $request->validate([
            'beneficiario_id' => 'required|integer',
            'oferta_id' => 'required|integer',
        ]);

        Suscripcion::where('beneficiario_id', $request->beneficiario_id)
            ->where('oferta_id', $request->oferta_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suscripciones eliminadas'
        ]);
    }
}
