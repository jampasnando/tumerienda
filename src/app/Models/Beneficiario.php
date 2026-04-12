<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiario extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'fechanac',
        'genero',
        'comentarios'
    ];
    public function colegios()
{
    return $this->hasMany(\App\Models\BeneficiarioColegio::class);
}

public function tutores()
{
    return $this->hasMany(\App\Models\BeneficiarioTutor::class);
}

public function colegioActivo()
{
    return $this->hasOne(\App\Models\BeneficiarioColegio::class)
        ->where('activo', 1);
        // ->latestOfMany();
}

public function tutorActivo()
{
    return $this->hasOne(\App\Models\BeneficiarioTutor::class)
        ->where('activo', true)
        ->latestOfMany();
}
}
