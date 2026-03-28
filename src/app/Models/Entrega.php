<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'subscripcion_id',
        'fecha',
        'estado',
        'observacion',
        'user_id'
    ];
    protected $casts = [
        'fecha' => 'date',
    ];
    public function subscripcion()
    {
        return $this->belongsTo(Subscripcion::class);
    }

    public function beneficiario()
    {
        return $this->subscripcion->beneficiario();
    }
}
