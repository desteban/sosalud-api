<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroUsuarioRequest;
use App\Models\User;

class RegistroController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function registrarUsuario(RegistroUsuarioRequest $request)
    {
        $usuario = User::create($request->validated());
    }
}
