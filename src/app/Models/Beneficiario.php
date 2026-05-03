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
        'comentarios',
        'colegio_id',
        'tutor_id',
        'activo'
    ];
    public function beneficiarioColegios()
    {
        return $this->hasMany(BeneficiarioColegio::class);
        // return $this->belongsToMany(Colegio::class,'beneficiario_colegio')
        //     ->withPivot(['activo','codigo'])
        //     ->withTimestamps();
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
public function nombrecolegioActivo()
{
    return $this->hasOne(\App\Models\BeneficiarioColegio::class)
        ->where('activo', 1)
        ->with('colegio');
        // ->latestOfMany();
}
public function tutorActivo()
{
    return $this->hasOne(\App\Models\BeneficiarioTutor::class)
        ->where('activo', true)
        ->latestOfMany();
}
public function beneficiariostutors()
{
    return $this->hasMany(\App\Models\BeneficiarioTutor::class);
}
}
