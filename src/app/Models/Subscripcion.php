<?php

namespace App\Models;

use App\Models\Entrega;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscripcion extends Model
{
    use SoftDeletes;
    protected $table = 'subscripciones';

    protected $fillable = [
        'beneficiario_id',
        'tutor_id',
        'menu_id',
        'gestion_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }
    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}
