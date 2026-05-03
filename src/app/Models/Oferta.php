<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oferta extends Model
{
    use SoftDeletes;
    protected $table = 'ofertas';

    protected $fillable = [
        // 'nombre',
        // 'fecha_inicio',
        // 'fecha_fin',
        // 'activo',
        'pack_id',
        'fecha'
    ];

    public function menus()
{
        return $this->belongsToMany(Menu::class,'menu_oferta', 'oferta_id', 'menu_id')->withPivot('grupo_id')->wherePivotNull('deleted_at');;
        // return $this->belongsToMany(Menu::class);
    }
    public function menuOfertas()
    {
        return $this->hasMany(MenuOferta::class);
    }
    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }
}
