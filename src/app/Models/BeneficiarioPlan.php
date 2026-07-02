<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioPlan extends Model
{
    use SoftDeletes;
    protected $table = 'beneficiario_plan';
    protected $fillable = [
        'beneficiario_id',
        'plan_id',
        'estado',
        'nrorecibidos',
        'detalle',
    ];

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
