<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Tutor extends Model
{
    use HasApiTokens, SoftDeletes;
    // protected $table='tutores';
    protected $fillable = [
        'nombre',
        'ci',
        'direccion',
        'telefono',
        'celular',
        'genero',
        'comentarios',
        'email',
        'password'
    ];
    public function beneficiarios()
{
    return $this->hasMany(\App\Models\BeneficiarioTutor::class);
}
}
