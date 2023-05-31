<?php

namespace App\Http\Controllers;

use App\Models\Tarjeta;
use Illuminate\Http\Request;

class TarjetaController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $user = auth()->user();

        try {
            $clientes = $user->clientes->where("activo", $query)->values()->toArray();

            if (empty($clientes)) {
                return response()->json(['message' => "No hay Clientes registrados"], 201);
            }

            return response()->json(['data' => $clientes], 201);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarjeta $tarjeta)
    {
        //
    }
}
