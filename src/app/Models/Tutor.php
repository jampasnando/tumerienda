<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use SoftDeletes;
    // protected $table='tutores';
    protected $fillable = [
        'nombre',
        'ci',
        'direccion',
        'telefono',
        'celular',
        'genero',
        'comentarios'
    ];
    public function beneficiarios()
{
    return $this->hasMany(\App\Models\BeneficiarioTutor::class);
}
}
