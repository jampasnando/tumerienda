<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arte extends Model
{
    protected $table = 'artes';
    protected $fillable = [
        'id',
        'marcologin',
        'aviso1'
    ];
}
