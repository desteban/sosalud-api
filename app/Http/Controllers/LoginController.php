<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $validacion = Validator::make($request->all(), [
            'nombreUsuario' => 'required',
            'password' => 'required'
        ]);

        if ($validacion->fails())
        {
            $respuesta = new Respuestas(400, '', 'Algo ha salido mal', $validacion->failed());
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $nombreUsuario = $request->input('nombreUsuario');
        $usuarioDB = DB::select("SELECT * from `usuarios` WHERE 
        `usuarios`.`email` = '$nombreUsuario' OR usuarios.`nombreUsuario` = '$nombreUsuario' LIMIT 1;");

        // notificar que las credenciales no son validas
        if (sizeof($usuarioDB) == 0 || !Hash::check($request->input('password'), $usuarioDB[0]->password))
        {
            $respuesta = new Respuestas(404, 'No se encontro el recurso solicitado', 'Credenciales invalidas');
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $respuesta = new Respuestas(200, 'succes', 'Todo bien');
        return response()->json($respuesta, $respuesta->codigoHttp);
    }
}
