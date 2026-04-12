<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiarioColegio extends Model
{
    protected $table = 'beneficiario_colegio';

    protected $fillable = [
        'beneficiario_id',
        'colegio_id',
        'activo',
        'tutor_id',
        'codigo'
    ];

    public function colegio()
{
    return $this->belongsTo(\App\Models\Colegio::class);
}

public function beneficiario()
{
    return $this->belongsTo(\App\Models\Beneficiario::class);
}
}
