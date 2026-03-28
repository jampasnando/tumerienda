<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestiones';
    protected $fillable = [
        'anio',
        'activo'
    ];
    public function beneficiariosGestion()
    {
        return $this->hasMany(BeneficiarioGestion::class);
    }
}
