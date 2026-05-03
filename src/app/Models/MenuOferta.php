<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOferta extends Model
{
    protected $table='menu_oferta';
    protected $fillable = [
        'grupo_id'
    ];
    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }
    public function menu(){
        return $this->belongsTo(Menu::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
