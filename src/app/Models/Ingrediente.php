<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = 'ingredientes';
    protected $fillable = [
        'nombre',
        'unidad',
        'costo_unitario',
        'categoria',
    ];
    public function ingredientesMenu()
    {
        return $this->hasMany(\App\Models\IngredienteMenu::class);
    }

}
