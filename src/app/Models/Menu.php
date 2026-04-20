<?php

namespace App\Models;

use App\Models\Ingrediente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table='menus';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'foto',
        'activo',
        'preparacion'

        // 'fechainicio',
        // 'fechafin'

    ];
    public function ofertas()
{
    return $this->belongsToMany(Oferta::class, 'menu_oferta', 'menu_id', 'oferta_id')->wherePivotNull('deleted_at');;
}
public function ingredientesMenu()
{
    return $this->hasMany(\App\Models\IngredienteMenu::class);
}
public function getCostoTotalAttribute()
{
    return $this->ingredientesMenu->sum('costo');
}

}
