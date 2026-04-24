<?php

namespace App\Http\Controllers;

// use App\Models\Pack;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function abiertos()
    {
        return Pack::with([
            'ofertas' => function ($query) {
                $query->select('id', 'pack_id', 'fecha');
            },
            'ofertas.menus' => function ($query) {
                $query->select('menus.id', 'nombre','tipo','foto');
            }
        ])
        ->where('estado', 'abierto')
        ->select('id', 'nombre', 'estado','precio')
        ->get();
    }
}
