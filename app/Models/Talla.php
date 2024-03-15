<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;
    //relacion de 1:N con zapato
    public function zapatos(){
        return $this->hasMany(Zapato::class);
    }
}
