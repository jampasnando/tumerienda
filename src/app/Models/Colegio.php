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
    public function cursos(){
        return $this->hasMany(Curso::class);
    }
    public function beneficiariosGestion()
    {
        return $this->hasMany(BeneficiarioGestion::class);
    }
}
