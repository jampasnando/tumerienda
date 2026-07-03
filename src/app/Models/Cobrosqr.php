<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobrosqr extends Model
{
    protected $table = 'cobrosqrs';
    protected $fillable = [
        'id',
        'alias',
        'numeroOrdenOriginante',
        'monto',
        'idQr',
        'moneda',
        'fechaproceso',
        'cuentaCliente',
        'nombreCliente',
        'documentoCliente',
        'fechareg'
    ];
}
