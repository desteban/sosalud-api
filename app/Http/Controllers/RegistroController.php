<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function registrarUsuario(Request $request)
    {
        $respuesta = new Respuestas();

        $validacion = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:usuarios,email'
        ]);

        if ($validacion->fails())
        {
            $respuesta->cambiarRespuesta(400, 'Mala peticion', 'Porfavor valide la informacion');
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $usuario = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => "md5($request->input('email'))"
        ];
        $usuarioDb = Usuarios::create($usuario);

        return response()->json($respuesta, $respuesta->codigoHttp);
    }
}
