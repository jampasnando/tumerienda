<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envase extends Model
{
    protected $table = 'envases';
    protected $fillable = [
        'nombre',
        'descripcion',
        'costo',
        'activo',
    ];
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
