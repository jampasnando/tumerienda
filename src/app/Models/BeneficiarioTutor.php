<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioTutor extends Model
{
    use SoftDeletes;

    protected $table='beneficiario_tutor';

    protected $fillable = [
        'beneficiario_id',
        'tutor_id',
        'tipo',
        'activo'
    ];

    public function tutor()
{
    return $this->belongsTo(\App\Models\Tutor::class);
}

public function beneficiario()
{
    return $this->belongsTo(\App\Models\Beneficiario::class);
}
}
