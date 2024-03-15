<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zapato extends Model
{
    use HasFactory;

    //definiendo relaciones inversas
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function talla(){
        return $this->belongsTo(Talla::class);
    } 

    //realacion 1:N con Imagen
    public function imagenes(){
        return $this->hasMany(Imagen::class);
    }

    //relacion 1:N con detalle_reserva
    public function detalle_reservas(){
        return $this->hasMany(Detalle_Reserva::class);
    }

 }
