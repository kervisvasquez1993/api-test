<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $query = $request->query("activos");
        $user = auth()->user();

        try {
            // $clientes = $user->clientes->where("activo", $query)->values()->toArray();
            $clientes = $user->clientes->all();

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
    public function store(Request $request)
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

        return response()->json(['message' => 'Cliente creado exitosamente', 'data' => $cliente], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $user_login_id = auth()->user()->id;
        if ($user_login_id != $cliente->user_id) {
            return response()->json(["error" => "No tiene permiso para ver esta información"], 400);
        }
        return response()->json(['data' => $cliente], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $user_login_id = auth()->user()->id;
        if ($user_login_id != $cliente->user_id) {
            return response()->json(["error" => "No tiene permiso efectuar esta accion"], 400);
        }
        $cliente->update($request->all());
        return response()->json(["data" => $cliente]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cambiarEstado($id)
    {
        
        $cliente = Cliente::findOrFail($id);
        $user_login_id = auth()->user()->id;
        if ($user_login_id != $cliente->user_id) {
            return response()->json(["error" => "No tiene permiso efectuar esta accion"], 400);
        }
        

        // Invertir el estado activo
        $cliente->activo = !$cliente->activo;

        $cliente->save();

        return response()->json(['data' => $cliente, 'message' => "Estado del cliente actualizado correct"]);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(['message' => 'Cliente eliminado correctamente', "data" => $cliente]);
    }
}

// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5OTM0OGRkOC00NjRhLTQyZmYtOTNhOC03ZWIyY2RlMjM2YWYiLCJqdGkiOiJkNTljMTY2ODY3YWFhZmJlZjZhNWE4ZjRjNzNlODM4NmU0YWZkYTI5MDE5ZTQwZGM4NDVhN2YyOTczMTI4MzI3OWM3YjY3N2M3YjQzMjg4ZCIsImlhdCI6MTY4NDUxMTA5OS4yMzE1NDIsIm5iZiI6MTY4NDUxMTA5OS4yMzE1NDIsImV4cCI6MTcxNjEzMzQ5OS4yMjg4ODIsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.XdloENBrS4NxdiAOszONinI2ZvhHsDfVFiSzGw5MYAs-msAmYUls9ZabHMeoz67QD5x0pSQMooVLV0LTPsOmcexloctAqHG53w1cLW3PJx6jVe8X8Je_vrcohcAoK7635cDteumy_j9l1M02SbZnzjGmOV3fL-s0MAipkioss19HTVvdXr4KkMT1ux6Jo2o7t9Tg_B-IN5koKIV2iMR9_zbdq-f4dwS97tnFwz5k2_TD0cZf7IFgiPzdPNrKT1Whyb2OITI1sEP0vOKS1bnpHmnejstMVmeY0BXe5SwPiTaUFMy-2nnyH1l_auWVe_M5zz6G5Cybu9gjqAExqBuiJsb1o7o-oICMCmGpi3H1aWl_uM-UuNLi5ltlrYo_7RAv23lJ6Voz_IgDKt88KepW3ZGck7ccmfSzKRWb18CPrDtAxw5SmhzN0TjQ_HjFLxQ4AAm2XEBLAwzPgqauhZ2MxukcrV89Ni4IAUwGutigC1HsL5yaiymu0gjAqFXxABrk9mioyvpiebPHmI18Ly7O9iYNPn9Dv62r-fGT3xhI4kA2n_FKS0J6VvXNg6TODuqZQnQBqh_GmQARa__s6p6XXCXrw2Z6GbHRcvi_ZXtyKpan0YrYvOCUAH4SHlDfkcWO-RWJd-t8xuHC1412eEXNbHfFXR8bEAYFsYT4fgeo6Zs

// notas : que sucede si el usuario registra una empresa con un correo y otro usuario regiustra otra empresa con otro usuario

// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5OTM0OGRkOC00NjRhLTQyZmYtOTNhOC03ZWIyY2RlMjM2YWYiLCJqdGkiOiIzYzdkMDAzNTg0MWZiN2NmYzdjOGEwM2Q5MzBhY2U3Yjg3ZDk0MzFmY2MwOGJlNGM4ZWRkYmUxZGIwNGIyZmUzMmVlNGVjNzVkNTRjZjZlOCIsImlhdCI6MTY4NDUyMDA5MS43MTc1NjcsIm5iZiI6MTY4NDUyMDA5MS43MTc1NjgsImV4cCI6MTcxNjE0MjQ5MS43MTE4NTgsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.hJQad4rZsH68S4D_rHGfQKH9aPZ_MORzYFS7JMdTpg7N9dIh3nIP6_i3H71X4zQPLp_czVgCw5_enQW6Gxob4RZl8MtB1z85LI7SK3dA5wLIsU05yr5NnwLToPb64yDuWPoyshnf9kwH1n9jQDBYEwVW6F0-7B09gcViTsqT2UyUWtPJWn_ZduM-cIKGpCuTgPbyqVwPU3iJ7AZOMKEmvyztgE8IxhnhDzPfqzPpE6zR9UrutD7PeZwhc622aZ_EsDCJ1CXy0vwbgEwmTQRylaF4ibjv5s7VRAgHd8Sc9wH9eXY-0uY5eWcW85LsDciuhyYsz0n5rg2zqn4GHJx8nExWvOUKt9K4qm2d-tUssZP5KUQ6hRNcRuJuRIWqilT7VD0zA3DJcqKwmidrOqx1UsSss1TlR3A3pSdM6qLFMH493yxQrM1DAU-0o-jZXAht4enwOHTRQ9kUqPDs2CkiEzPbnK3TPhpkQ7658EckqY0cA0_wcNjUvSpH0v0ZIqplgpJ8WO5ABkCdrA3xBxtTzGRmISu4sfiJYtd3JuzzAtuCpZea6dxamzVE39wrQPkONaK7FpXTz9vpcT_qfAuYxBmMngPqGmUOFjAcDduXEzuI3npEmmhxqPZ8lrEFuCE5oB_7egE7OI97ru2mcUT4TIDbG0WtfJ1Mpcukh8oQBW4
