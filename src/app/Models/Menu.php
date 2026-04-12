<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table='menus';
    protected $fillable = [
        'nombre',
        'descripcion',
        'costo',
        'precio',
        'foto',
        'activo',
        'ingredientes',
        'preparacion'

        // 'fechainicio',
        // 'fechafin'

    ];
    public function ofertas()
{
    return $this->belongsToMany(Oferta::class, 'menu_oferta', 'menu_id', 'oferta_id');
}
}
