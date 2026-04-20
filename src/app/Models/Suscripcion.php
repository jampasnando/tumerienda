<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suscripcion extends Model
{
    use SoftDeletes;
    protected $table = 'suscripciones';

    protected $fillable = [
        'beneficiario_id',
        'oferta_id',
        'menu_id',
        'colegio_id',
        'tutor_id',
        'fecha',
        'estado'
    ];
    protected $dates = ['fecha'];
    public function beneficiario()
{
    return $this->belongsTo(Beneficiario::class);
}

public function menu()
{
    return $this->belongsTo(Menu::class);
}

public function oferta()
{
    return $this->belongsTo(Oferta::class);
}
}
