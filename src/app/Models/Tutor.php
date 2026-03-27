<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use SoftDeletes;
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
        return $this->belongsToMany(Beneficiario::class, 'beneficiario_tutor')
            ->withPivot(['tipo', 'estado'])
            ->withTimestamps();
    }
    public function beneficiariotutores()
    {
        $this->hasMany(Beneficiario::class);
    }
}
