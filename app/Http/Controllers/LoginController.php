<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use App\Util\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        if (empty($usuarioDB) || !Hash::check($request->input('password'), $usuarioDB[0]->password))
        {
            $respuesta = new Respuestas(404, 'No se encontro el recurso solicitado', 'Credenciales invalidas');
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $usuario = [
            'id' => $usuarioDB[0]->id,
            'nombreUsuario' => $usuarioDB[0]->nombreUsuario,
            'email' => $usuarioDB[0]->email,
        ];

        $duracionToken = env('TOKEN_DURACION');
        $time = time();
        $duracion = $time + (60 * 60 * (24 * $duracionToken));
        $jwt = Token::crear(data: $usuario, creacion: $time, duracion: $duracion);
        Token::guardarToken([
            'token' => $jwt,
            'usuario_id' => $usuario['id'],
            'creado' => Carbon::now(),
            'expire' => date('Y/m/d H:i:s', $duracion)
        ]);

        $respuesta = new Respuestas(201, 'succes', 'Todo bien', [
            'token' => $jwt,
            'usuarios' => $usuario
        ]);
        return response()->json($respuesta, $respuesta->codigoHttp);
    }

    public function validar(Request $request)
    {
        $respuesta = new Respuestas();

        $token = $request->header('token');
        $decode = Token::decodificar($token);

        $respuesta->data = [
            'decode' => $decode
        ];

        return response()->json($respuesta, $respuesta->codigoHttp);
    }
}
