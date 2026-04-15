<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngredienteMenu extends Model
{
    //
   use SoftDeletes;

    protected $table = 'ingrediente_menu';

    protected $fillable = [
        'menu_id',
        'ingrediente_id',
        'cantidad',
        'costo',
    ];

    public function ingrediente()
    {
        return $this->belongsTo(\App\Models\Ingrediente::class);
    }

    public function menu()
    {
        return $this->belongsTo(\App\Models\Menu::class);
    }
}
