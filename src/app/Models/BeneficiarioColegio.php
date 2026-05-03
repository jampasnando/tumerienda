<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioColegio extends Model
{
    use SoftDeletes;
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
