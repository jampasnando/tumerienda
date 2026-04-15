<?php

namespace App\Http\Controllers;

use App\Helpers\CodigoColegio;
use App\Models\Beneficiario;
use App\Models\BeneficiarioColegio;
use App\Models\BeneficiarioGestion;
use App\Models\BeneficiarioTutor;
use App\Models\Colegio;
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
        $beneficiario = Beneficiario::create([
            'nombre' => $request->nombre,
            'fechanac' => date('Y-m-d', strtotime($request->fechanac)),
            'genero' => $request->genero,
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
        $colegio = Colegio::find($request->colegio_id);

        $codigo= CodigoColegio::generar(
            $colegio->nombre,
            $beneficiario->id
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
}
