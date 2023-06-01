<?php

namespace App\Http\Controllers;

use App\Models\Tarjeta;
use App\Models\Cliente;
use App\Models\TarjetaCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TarjetaController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user();

        try {
            $tarjetas = $user->tarjetas->all();

            if (empty($tarjetas)) {
                return response()->json(['message' => "No hay Tarjetas registrados"], 201);
            }

            return response()->json(['data' => $tarjetas], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(Tarjeta $tarjeta)
    {
        $user_login_id = auth()->user()->id;

        if ($user_login_id != $tarjeta->user_id) {
            return response()->json(["error" => "No tiene permiso para ver esta información"], 400);
        }
        return response()->json(['data' => $tarjeta], 201);
    }

    /**
     * Update the specified resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'src_img' => 'required|image|max:2048',
        ]);

        $tarjeta = Tarjeta::create([
            'src_img' => Storage::disk('s3')->put('producto', $request->file('src_img'), 'public'),
            'user_id' => auth()->user()->id,
        ]);

        return response()->json(['message' => 'Tarjeta Agregada con Exito', 'data' => $tarjeta], 201);
    }
    public function update(Request $request, Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarjeta $tarjeta)
    {
        $user_login_id = auth()->user()->id;

        if ($user_login_id != $tarjeta->user_id) {
            return response()->json(["error" => "No tiene permiso para eliminar esta tarjeta"], 400);
        }

        $tarjeta->delete();

        return response()->json(['message' => 'Tarjeta eliminada exitosamente'], 200);
    }

    public function tarjetaCliente(Request $request, Tarjeta $tarjeta)
    {
        $validatedData = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'empresa' => 'required',
            'numero_telefono' => 'required',
            'correo' => 'required|email',
            'cultivo' => 'required',
            'ubicacion_zona' => 'required',
            'pais' => 'required',
            'tamano_de_cultivo' => 'required',
        ]);

        $cliente = Cliente::create([
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'empresa' => $validatedData['empresa'],
            'numero_telefono' => $validatedData['numero_telefono'],
            'correo' => $validatedData['correo'],
            'cultivo' => $validatedData['cultivo'],
            'ubicacion_zona' => $validatedData['ubicacion_zona'],
            'pais' => $validatedData['pais'],
            'tamano_de_cultivo' => $validatedData['tamano_de_cultivo'],
            'user_id' => auth()->user()->id,
        ]);

        $tarjetaCliente = TarjetaCliente::create([
            "user_id" => auth()->user()->id,
            "id_cliente" => $cliente->id,
            "id_tarjeta" => $tarjeta->id,
        ]);

        return response()->json(['message' => 'Cliente creado exitosamente', 'data' => $cliente], 201);

    }
    public function clienteTarjeta(Request $request, Cliente $cliente)
    {
        
        $request->validate([
            'src_img' => 'required',
        ]);

        $tarjeta = Tarjeta::create([
            'src_img' => Storage::disk('s3')->put('producto', $request->file('src_img'), "public"),
            'user_id' => auth()->user()->id,
        ]);
        $tarjetaCliente = TarjetaCliente::create([
            "user_id" => auth()->user()->id,
            "id_cliente" => $cliente->id,
            "id_tarjeta" => $tarjeta->id,
        ]);

        return response()->json(['message' => 'Tarjeta creada de forma exitosamente', 'data' => $tarjeta], 201);

    }
}
