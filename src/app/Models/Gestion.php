<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestiones';
    protected $fillable = [
        'anio',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
    ];
    public function beneficiariosGestion()
    {
        return $this->hasMany(BeneficiarioGestion::class);
    }
}
