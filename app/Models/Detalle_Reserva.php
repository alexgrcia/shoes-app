<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Reserva extends Model
{
    use HasFactory;

    protected $table = "detalle_reservas";

    public function zapato(){
        return $this->belongsTo(Zapato::class);
    }

    public function reserva(){
        return $this->belongsTo(Reserva::class);
    }
}
