<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oferta extends Model
{
    use SoftDeletes;
    protected $table = 'ofertas';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class,'menu_oferta', 'oferta_id', 'menu_id')->wherePivotNull('deleted_at');;
    }
}
