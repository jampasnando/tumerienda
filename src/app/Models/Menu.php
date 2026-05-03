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
        // 'costo',
        'foto',
        'activo',
        'preparacion',
        'tipo'

        // 'fechainicio',
        // 'fechafin'

    ];
    public function ofertas()
{
    return $this->belongsToMany(Oferta::class, 'menu_oferta', 'menu_id', 'oferta_id')->withPivot('grupo')->wherePivotNull('deleted_at');;
}
public function ingredientesMenu()
{
    return $this->hasMany(\App\Models\IngredienteMenu::class);
}
public function getCostoTotalAttribute()
{
    return $this->ingredientesMenu()
        ->join('ingredientes', 'ingredientes.id', '=', 'ingrediente_menu.ingrediente_id')
        ->selectRaw('SUM((ingrediente_menu.cantidad / ingredientes.equivalencia) * ingredientes.costo_unitario) as total')
        ->value('total') ?? 0;

}
    public function menuOfertas()
    {
        return $this->hasMany(MenuOferta::class);
    }

}
