<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Lógica para crear un nuevo usuario
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar un usuario existente
    }

    public function destroy($id)
    {
        // Lógica para eliminar un usuario
    }
}
