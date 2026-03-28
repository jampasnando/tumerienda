<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioGestion extends Model
{
    use SoftDeletes;

    protected $table = 'beneficiario_gestion';

    protected $fillable = [
        'beneficiario_id',
        'colegio_id',
        'curso_id',
        'gestion_id',
        'estado',
    ];

    // 🔗 Relaciones

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }
    public function getTituloAttribute()
    {
        return "{$this->gestion?->anio} - {$this->colegio?->nombre} - {$this->curso?->nombre}";
    }
}
