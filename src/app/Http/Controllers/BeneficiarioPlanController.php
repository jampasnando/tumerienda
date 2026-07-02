<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioPlan;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeneficiarioPlanController extends Controller
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
            Log::info('Datos recibidos para crear BeneficiarioPlan: ', $request->all());
            $request->validate([
                'beneficiario_id' => 'required|exists:beneficiarios,id',
                'plan_id' => 'required|exists:plans,id',
                'estado' => 'required|boolean',
                'nrorecibidos' => 'required|integer',
                'detalle' => 'nullable|string',
            ]);
            BeneficiarioPlan::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(BeneficiarioPlan $beneficiarioPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeneficiarioPlan $beneficiarioPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BeneficiarioPlan $beneficiarioPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeneficiarioPlan $beneficiarioPlan)
    {
        //
    }
    public function planesBeneficiario($beneficiarioId)
    {
        $planes = BeneficiarioPlan::with('plan')
            ->where('beneficiario_id', $beneficiarioId)
            ->get();
            // ->map(function ($bp) {
            //     return [
            //         'id' => $bp->id,
            //         'plan_id' => $bp->plan_id,
            //         'plan_nombre' => $bp->plan->nombre,
            //         'plan_precio' => $bp->plan->precio,
            //         'plan_nroentregas' => $bp->plan->nroentregas,
            //         'fecha_inicio' => $bp->fecha_inicio,
            //         'fecha_fin' => $bp->fecha_fin,
            //     ];
            // });

        return response()->json($planes);
    }

}
