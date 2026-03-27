<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioTutor extends Model
{
    use SoftDeletes;

    // protected $table='beneficiario_tutor';

    protected $fillable = [
        'beneficiario_id',
        'tutor_id',
        'tipo',
        'estado'
    ];
    public function beneficiario()
    {
        $this->belongsTo(Beneficiario::class);
    }
    public function tutores()
    {
        $this->belongsTo(Tutor::class);
    }
}
