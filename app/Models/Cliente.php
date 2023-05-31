<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [
        "nombre",
        "apellido",
        "empresa",
        "numero_telefono",
        "correo",
        "cultivo",
        "ubicacion_zona",
        "pais",
        "tamano_de_cultivo",
        "user_id",
        "activo",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tarjetas()
    {
        return $this->hasMany(TarjetaCliente::class, 'id_cliente', 'id');
    }

}
