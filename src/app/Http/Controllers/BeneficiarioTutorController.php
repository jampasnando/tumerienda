<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioTutor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BeneficiarioTutorController extends Controller
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
    public function show(BeneficiarioTutor $beneficiarioTutor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeneficiarioTutor $beneficiarioTutor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BeneficiarioTutor $beneficiarioTutor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeneficiarioTutor $beneficiarioTutor)
    {
        //
    }
    public function entregashoy($tutor_id)
{
    $hoy = Carbon::today()->toDateString();

    $entregas = BeneficiarioTutor::where('tutor_id', $tutor_id)
        ->with([
            'beneficiario',
            'beneficiario.beneficiariosuscripciones' => function ($query) use ($hoy) {
                $query->whereDate('fecha', $hoy)
                    ->with('menu');
            }
        ])
        ->get();

    return response()->json($entregas);
}
}
