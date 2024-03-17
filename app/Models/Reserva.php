<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    //relaciones entre los modelos
    public function usuario(){
        return $this->belongsTo(User::class);
    }

    //relacion de 1:N con detalle_Reserva
    public function detalleReservas(){
        return $this->hasMany(Detalle_Reserva::class);
    }

}
