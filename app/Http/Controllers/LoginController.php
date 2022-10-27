<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use App\Util\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'nombreUsuario' => 'required|alpha_num',
                'password' => 'required|alpha_num'
            ],
            messages: [
                'nombreUsuario' => 'El nombre de usario es necesario',
                'nombreUsuario.alpha_num' => 'El nombre de usuario debe ser alfanumérico',
                'password.required' => 'La contraseña es necesaria',
                'password.alpha_num' => 'La contraseña debe ser alfanumérico'
            ]
        );

        if ($validacion->fails())
        {
            $respuesta = new Respuestas(
                codigoHttp: 400,
                titulo: 'Bad Request',
                mensaje: 'Se han presentado fallos en la información ',
                data: $validacion->getMessageBag()
            );
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
            'nombreUsuario' => $usuarioDB[0]->nombreUsuario
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
}
