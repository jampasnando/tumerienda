<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colegio extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'celular',
        'contacto',
        'latitud',
        'longitud'
    ];
    public function beneficiarios()
    {
        return $this->belongsToMany(Beneficiario::class, 'beneficiario_colegio')
            ->withPivot(['id', 'activo', 'tutor_id', 'codigo'])
            ->withTimestamps();
    }
}
