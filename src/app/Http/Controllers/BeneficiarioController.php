<?php

namespace App\Http\Controllers;

use App\Helpers\CodigoColegio;
use App\Models\Beneficiario;
use App\Models\BeneficiarioColegio;
use App\Models\BeneficiarioPlan;
use App\Models\BeneficiarioTutor;
use App\Models\Colegio;
use App\Models\Oferta;
use App\Models\Suscripcion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeneficiarioController extends Controller
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
        Log::info('Datos recibidos para crear beneficiario: ', $request->all());
        $request->validate([
            'nombre' => 'required',
            'fechanac' => 'required',
            'colegio_id' => 'required',
            'tutor_id' => 'required',
        ]);

        $user = $request->user();

        // 🔹 1. Crear beneficiario
        $colegio = Colegio::find($request->colegio_id);

        $correlativo = BeneficiarioColegio::where('colegio_id', $request->colegio_id)->count() + 1;
        $codigo = CodigoColegio::generar(
            $colegio->nombre,
            $correlativo
        );

        $beneficiario = Beneficiario::create([
            'nombre' => $request->nombre,
            'fechanac' => date('Y-m-d', strtotime($request->fechanac)),
            'genero' => $request->genero,
            'codigo' => $codigo,
            'comentarios' => $request->comentarios,
            'activo' => true,
        ]);

        // 🔹 2. Relación tutor
        BeneficiarioTutor::create([
            'beneficiario_id' => $beneficiario->id,
            'tutor_id' => $request->tutor_id,
            'tipo' => 'padre',
            'activo' => true,
        ]);
        $codigo = CodigoColegio::generar(
            $colegio->nombre,
            $correlativo
        );
        Log::info('Código generado para beneficiario: ' . $codigo);
        BeneficiarioColegio::create([
            'beneficiario_id' => $beneficiario->id,
            'colegio_id' => $request->colegio_id,
            'activo' => true,
            'tutor_id' => $request->tutor_id,
            'codigo' => $codigo
        ]);
        // // 🔹 3. Obtener gestión activa
        // $gestion = \App\Models\Gestion::where('activo', 1)->first();

        // // 🔹 4. Crear beneficiario_gestion
        // BeneficiarioGestion::create([
        //     'beneficiario_id' => $beneficiario->id,
        //     'colegio_id' => $request->colegio_id,
        //     'curso_id' => $request->curso_id,
        //     'gestion_id' => $gestion->id,
        //     'estado' => 'activo',
        // ]);

        return response()->json($beneficiario);
    }


    /**
     * Display the specified resource.
     */
    public function show(Beneficiario $beneficiario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beneficiario $beneficiario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beneficiario $beneficiario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beneficiario $beneficiario)
    {
        //
    }
    //


    public function calendarioBeneficiario($beneficiarioId)
    {
        $mes = request('mes', Carbon::now()->month);
        $anio = request('anio', Carbon::now()->year);
        // Log::info('mes, ano, beneficairioId: ',["mes"=>$mes,"ano"=>$anio,"idbenef"=>$beneficiarioId]);
        $inicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fin = Carbon::create($anio, $mes, 1)->endOfMonth();

        $ofertas = Oferta::query()
            ->where('activo', 1)
            ->whereBetween('fecha', [$inicio, $fin])

            ->with([
                'suscripciones' => function ($q) use ($beneficiarioId) {

                    $q->where('beneficiario_id', $beneficiarioId);
                }
            ])

            ->orderBy('fecha')
            ->get();
        Log::inf("suscripcioens",["ofertasconsusc"=>$ofertas]);
        $resultado = $ofertas->map(function ($oferta) {

            $suscripcion = $oferta->suscripciones->first();

            $estado = null;
            $color = 'gray';

            if ($suscripcion) {

                $estado = $suscripcion->estado;

                switch ($suscripcion->estado) {

                    case 'pendiente':
                        $color = 'orange';
                        break;

                    case 'entregado':
                        $color = 'green';
                        break;

                    case 'noentregado':
                        $color = 'red';
                        break;
                }
            }

            return [
                'fecha' => $oferta->fecha,
                'oferta_id' => $oferta->id,
                'suscripcion_id' => $suscripcion?->id,
                'estado' => $estado,
                'color' => $color,
            ];
        });

        $planesDelBeneficiario = BeneficiarioPlan::where('beneficiario_id', $beneficiarioId)
            ->with('plan')
            ->get();

        $totalEntregasPlanes = $planesDelBeneficiario->sum(function ($beneficiarioPlan) {
            return (int) optional($beneficiarioPlan->plan)->nroentregas;
        });

        $totalSuscripcionesOfertas = Suscripcion::where('beneficiario_id', $beneficiarioId)
            ->distinct('oferta_id')
            ->count('oferta_id');

        $totalSuscripcionesFechas = Suscripcion::where('beneficiario_id', $beneficiarioId)
            ->distinct('fecha')
            ->count('fecha');

        return response()->json([
            'mes' => (int)$mes,
            'anio' => (int)$anio,
            'total_entregas_planes' => $totalEntregasPlanes,
            'total_suscripciones_ofertas' => $totalSuscripcionesOfertas,
            'total_suscripciones_fechas' => $totalSuscripcionesFechas,
            'items' => $resultado,
        ]);
    }
    public function beneficiarioPlanes($beneficiarioId)
    {
        $planes = BeneficiarioPlan::with('plan')
            ->where('beneficiario_id', $beneficiarioId)
            ->where('estado', 1)
            ->get();

        return response()->json($planes);
    }
}
