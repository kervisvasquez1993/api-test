<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarjetaCliente extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_cliente",
        "id_tarjeta"
    ];
}
