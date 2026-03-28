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
        'codigo',
        'comentarios'
    ];
    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'beneficiario_tutor','beneficiario_id','tutor_id')
            ->withPivot(['tipo', 'estado'])
            ->withTimestamps();
    }
    public function beneficiariotutors()
    {
        return $this->hasMany(BeneficiarioTutor::class);
    }
    public function gestiones()
    {
        return $this->hasMany(BeneficiarioGestion::class);
    }
    public function subscripciones()
    {
        return $this->hasMany(Subscripcion::class);
    }
}
