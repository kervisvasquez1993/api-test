<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('empresa');
            $table->string('numero_telefono');
            $table->string('correo')->unique();
            $table->enum('cultivo', ['Caña de Azúcar', 'Banano', 'Plátano', "Aguacate", "Café", "Ornamentales", "Hortalizas", "Palma de Aceite", "Melon", "Sandia", "Otro"])->default('Otro');
            $table->string("ubicacion_zona");
            $table->string('pais');
            $table->string('tamano_de_cultivo');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean("activo")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
