<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;
    protected $fillable = [
        "src_image",
    ];
    public function clientes()
    {
        return $this->hasMany(TarjetaCliente::class, 'id_tarjeta', 'id');
    }

}
