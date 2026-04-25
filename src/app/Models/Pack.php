<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pack extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'estado',
        'precio',
        'descripcion'
    ];
    public function ofertas()
    {
        return $this->hasMany(\App\Models\Oferta::class);
    }
}
