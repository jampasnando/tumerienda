<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente extends Model
{
    use SoftDeletes;
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
