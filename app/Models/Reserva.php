<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    //relaciones entre los modelos
    public function user(){
        return $this->belongsTo(User::class);
    }

    //relacion de 1:N con detalle_Reserva
    public function detalle_reservas(){
        return $this->hasMany(Detalle_Reserva::class);
    }

}
