<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'nivel',
        'estado'
    ];
    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }
}
