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
    public function beneficiarioColegios()
    {
        return $this->hasMany(BeneficiarioColegio::class);
        // return $this->belongsToMany(Beneficiario::class, 'beneficiario_colegio')
        //     ->withPivot(['activo', 'tutor_id', 'codigo'])
        //     ->withTimestamps();
    }
}
