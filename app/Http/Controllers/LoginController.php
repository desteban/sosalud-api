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
            $respuesta = new Respuestas(404, 'Not Found', 'Credenciales invalidas');
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

    public function verificarToken(Request $request)
    {
        $validador = Validator::make(
            data: $request->all(),
            rules: [
                'token' => 'required',
                'auth' => 'required|boolean',
            ],
            messages: [
                'token.required' => 'El token es necesario',
                'auth.required' => 'EL campo auth es necesario',
                'auth.boolean' => 'El camo auth debe ser de tipo boolean',
            ]
        );

        $token = $request->input('token');
        $auth = $request->input('auth');
        $decode = Token::decodificar($token);

        if ($validador->fails())
        {
            $respuesta = new Respuestas(
                codigoHttp: 400,
                titulo: 'bad request',
                mensaje: 'Algo salio mal',
                data: [
                    'validacion' => $validador->getMessageBag(),
                    'token' => 'token no valido',
                ]
            );
            return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
        }

        $respuesta = new Respuestas();
        if (!$auth)
        {

            $usuario = DB::selectOne(
                query: 'SELECT id FROM usuarios WHERE remember_token=?',
                bindings: [
                    $token,
                ],
            );

            if (empty($usuario))
            {
                $respuesta->cambiarRespuesta(
                    codigoHttp: 404,
                    titulo: 'not found',
                    mensaje: 'No entramos el token de acceso',
                );
            }
        }

        if ($auth)
        {
            $acceso = DB::selectOne(
                query: 'SELECT * FROM personal_access_tokens WHERE token=? AND usuario_id=?',
                bindings: [
                    $token,
                    $decode->data->id,
                ]
            );

            if (empty($acceso))
            {
                $respuesta->cambiarRespuesta(
                    codigoHttp: 404,
                    titulo: 'not found',
                    mensaje: 'No entramos el token de acceso',
                );
            }
        }

        return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
    }
}
