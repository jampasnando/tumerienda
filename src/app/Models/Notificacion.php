<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table="notificaciones";
    protected $fillable = [
        'notificaciones',
        'donde',
        'estado',
        'logo',
        'color'
    ];
}
