<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioTutor;
use App\Models\Subscripcion;
use Illuminate\Http\Request;

class SubscripcionController extends Controller
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
        $request->validate([
            'beneficiario_id' => 'required',
            'menu_id' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        $user = $request->user();

        // 🔥 obtener tutor del usuario
        $tutorId = $user->tutor_id;

        // 🔥 obtener relación activa
        $rel = BeneficiarioTutor::where('beneficiario_id', $request->beneficiario_id)
            ->where('tutor_id', $tutorId)
            ->where('estado', 'activo')
            ->first();

        if (!$rel) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // 🔥 obtener gestion/curso/colegio desde beneficiario_gestion
        $bg = \App\Models\BeneficiarioGestion::where('beneficiario_id', $request->beneficiario_id)
            ->where('estado', 'activo')
            ->first();

        // 🚀 crear subscripción
        $sub = Subscripcion::create([
            'beneficiario_id' => $request->beneficiario_id,
            'tutor_id' => $tutorId,
            'menu_id' => $request->menu_id,
            'gestion_id' => $bg->gestion_id,
            'colegio_id' => $bg->colegio_id,
            'curso_id' => $bg->curso_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => 'activo',
        ]);

        return $sub;
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscripcion $subscripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscripcion $subscripcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscripcion $subscripcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscripcion $subscripcion)
    {
        //
    }
}
