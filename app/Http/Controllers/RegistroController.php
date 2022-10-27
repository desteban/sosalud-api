<?php

namespace App\Http\Controllers;

use App\Mail\RecuperacionMailable;
use App\Mail\RegistroMailable;
use App\Models\Respuestas;
use App\Models\Usuarios;
use App\Util\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function registrarUsuario(Request $request)
    {
        $respuesta = new Respuestas(201, 'Creado', 'Usuario registrado exitosamente', $request->all());

        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'nombre' => 'required',
                'nombreUsuario' => 'required|unique:usuarios,nombreUsuario|alpha_num',
                'email' => 'required|unique:usuarios|email',
            ],
            messages: [
                'nombre.required' => 'El nombre es necesario',
                'nombre.alpha_num' => 'El nombre debe ser alfanumérico',
                'nombreUsuario.required' => 'El nombre de usuario es necesario',
                'nombreUsuario.unique' => 'Usuario ya registrado',
                'nombreUsuario.alpha_num' => 'El nombre de usuario debe ser alfanumérico',
                'email.required' => 'El email es necesario',
                'email.unique' => 'El email ya se encuentra registrado',
            ]
        );

        if ($validacion->fails())
        {
            $respuesta->cambiarRespuesta(
                codigoHttp: 400,
                titulo: 'Mala peticion',
                mensaje: 'Porfavor valide la informacion',
                data: $validacion->getMessageBag()
            );
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $token = md5($request->input('email'));
        $usuario = new Usuarios([
            'email' => $request->input('email'),
            'name' => $request->input('nombre'),
            'nombreUsuario' => $request->input('nombreUsuario'),
            'password' => $token,
        ]);
        $usuario->remember_token = $token;
        $usuario->save();

        try
        {
            $correo = new RegistroMailable($usuario->name, $usuario->nombreUsuario, $token);
            Mail::to($usuario['email'])->send($correo);

            $respuesta->data = $usuario;
            return response()->json($respuesta, $respuesta->codigoHttp);
        }
        catch (\Throwable $th)
        {
            $respuesta->cambiarRespuesta(200, 'Hubo un error en el servidor y la solicitud no pudo ser completada');
        }
    }

    public function recuperarPassword(Request $request)
    {
        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'password' => 'required|alpha_num|min:6',
                'confirm' => 'required|alpha_num|same:password',
                'rememberToken' => 'required',
            ],
            messages: [
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe contar con almenos 6 caracteres',
                'confirm.required' => 'El campo de confirmacion de contraseña es necesario',
                'confirm.same' => 'Las contraseñas deben coincidir',
                'rememberToken.required' => 'No se ha encontrado al usuario'
            ]
        );

        if (!$validacion->fails())
        {
            try
            {
                $password = bcrypt($request->input('password'));
                $rememberToken = $request->input('rememberToken');
                $fecha = now();
                $usuario = DB::select(
                    query: "SELECT * FROM usuarios WHERE remember_token=?",
                    bindings: [$rememberToken]
                );

                if (!empty($usuario))
                {
                    $usuarioId = DB::select(
                        query: 'SELECT id FROM usuarios WHERE remember_token=?',
                        bindings: [$rememberToken]
                    );

                    //actualizar datos del usuario
                    DB::update(
                        query: "UPDATE usuarios 
                                    SET password=?, email_verified_at=?, updated_at=?, remember_token=NULL
                                    WHERE remember_token=?",
                        bindings: [
                            $password,
                            $fecha,
                            $fecha,
                            $rememberToken,
                        ]
                    );

                    //eliminar tokens del usuario
                    DB::delete(
                        query: "DELETE from personal_access_tokens WHERE usuario_id = ?",
                        bindings: [
                            $usuarioId[0]->id
                        ]
                    );

                    $respuesta = new Respuestas(
                        codigoHttp: 200,
                        titulo: 'succes',
                        mensaje: '',
                    );
                    return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
                }
            }
            catch (\Throwable $th)
            {
                $respuesta = new Respuestas(
                    codigoHttp: 500,
                    titulo: 'Internal Server Error',
                    mensaje: 'Algo salio mal',
                    data: $validacion->getMessageBag()
                );
                return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
            }
        }

        $respuesta = new Respuestas(
            codigoHttp: 400,
            titulo: 'Bad Request',
            mensaje: 'Hemos encontrado algunos errores en los datos recibidos',
            data: $validacion->getMessageBag()
        );
        return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
    }

    public function pedirCambio(Request $request)
    {
        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'email' => 'required|email'
            ],
            messages: [
                'email.required' => 'El email es necesario',
                'email.email' => 'Debes ingresar un email valido'
            ]
        );

        if (!$validacion->fails())
        {
            $usuario = DB::select(
                query: "SELECT id, email FROM usuarios WHERE email=?",
                bindings: [
                    $request->input('email')
                ]
            );

            if (!empty($usuario))
            {
                $data = [
                    'id' => $usuario[0]->id,
                ];
                $creacion = time();
                $duracion = $creacion + (60 * 15 * 1);
                $token = Token::crear(data: $data, creacion: $creacion, duracion: $duracion);

                DB::statement(
                    query: "UPDATE usuarios
                    SET remember_token=? WHERE id=?",
                    bindings: [
                        $token,
                        $usuario[0]->id,
                    ]
                );

                $correo = new RecuperacionMailable(token: $token);
                Mail::to($usuario[0]->email)->send($correo);

                $respuesta = new Respuestas(
                    codigoHttp: 200,
                    titulo: '',
                    mensaje: 'Token de cambio de contraseña generado',
                    data: [
                        'duracion' => $duracion
                    ]
                );
                return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
            }
        }

        $respuesta = new Respuestas(
            codigoHttp: 400,
            titulo: 'Bad Request',
            mensaje: 'Error en los datos enviados',
            data: $validacion->getMessageBag()
        );
        return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
    }
}
