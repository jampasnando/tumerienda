<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $table = 'plans';
    protected $fillable = [
        'nombre',
        'estado',
        'precio',
        'nroentregas',
        'descripcion',
        'qr'
    ];
    public function beneficiarios()
    {
        return $this->hasMany(BeneficiarioPlan::class);
    }
}
