<?php

namespace App\Http\Controllers;

use App\Mail\CambioPasswordMailable;
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

        $token = Token::crear(
            data: [
                'email' => $request->input('email')
            ]
        );
        $usuario = new Usuarios([
            'email' => $request->input('email'),
            'name' => $request->input('nombre'),
            'nombreUsuario' => $request->input('nombreUsuario'),
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
                'confirmPassword' => 'required|alpha_num|same:password',
                'rememberToken' => 'required',
            ],
            messages: [
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe contar con almenos 6 caracteres',
                'confirmPassword.required' => 'El campo de confirmacion de contraseña es necesario',
                'confirmPassword.same' => 'Las contraseñas deben coincidir',
                'rememberToken.required' => 'No se ha encontrado al usuario'
            ]
        );

        if ($validacion->fails())
        {
            $respuesta = new Respuestas(
                codigoHttp: 400,
                titulo: 'Bad Request',
                mensaje: 'Valida los datos',
                data: $validacion->getMessageBag()
            );
            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        $password = $request->input('password');
        $rememberToken = $request->input('rememberToken');
        $decode = Token::decodificar($rememberToken);
        $usuario = DB::selectOne(
            query: "SELECT id, email, email_verified_at FROM usuarios WHERE remember_token=?",
            bindings: [$rememberToken]
        );

        if (!empty($usuario))
        {

            //activar cuenta
            if (empty($usuario->email_verified_at))
            {
                $this->actualizarUsuario(password: $password, rememberToken: $rememberToken, usuario: $usuario);
            }

            //actualizar contraseña
            if (!empty($usuario->email_verified_at) && !empty($decode))
            {
                $this->actualizarUsuario(password: $password, rememberToken: $rememberToken, usuario: $usuario);
            }

            $correo = new CambioPasswordMailable();
            Mail::to($usuario->email)->send($correo);

            $respuesta = new Respuestas(
                codigoHttp: 200,
                titulo: 'succes',
                mensaje: 'Cuenta actualizada'
            );
            return response()->json($respuesta, $respuesta->codigoHttp);
        }



        $respuesta = new Respuestas(
            codigoHttp: 404,
            titulo: 'Not Found',
            mensaje: 'Valida los datos',
            data: [
                'usuario' => 'No encontramos el usuario',
            ]
        );
        return response()->json($respuesta, $respuesta->codigoHttp);
    }

    public function pedirCambio(Request $request)
    {
        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'nombreUsuario' => 'required'
            ],
            messages: [
                'nombreUsuario.required' => 'El nombre de usuario o email son necesarios',
            ]
        );

        if ($validacion->fails())
        {
            $respuesta = new Respuestas(
                codigoHttp: 400,
                titulo: 'Bad Request',
                mensaje: 'Error en los datos enviados',
                data: $validacion->getMessageBag()
            );
            return response()->json(data: $respuesta, status: $respuesta->codigoHttp);
        }

        $nombreUsuario = $request->input('nombreUsuario');
        $usuario = DB::selectOne(
            query: 'SELECT id, email FROM usuarios WHERE email=? OR nombreUsuario=?',
            bindings: [
                $nombreUsuario,
                $nombreUsuario
            ]
        );

        if (!empty($usuario))
        {
            $data = [
                'id' => $usuario->id,
            ];
            $creacion = time();
            //15 minutos de duracion del token
            $duracion = $creacion + (60 * 15 * 1);
            $token = Token::crear(data: $data, creacion: $creacion, duracion: $duracion);

            DB::statement(
                query: "UPDATE usuarios
                    SET remember_token=? WHERE id=?",
                bindings: [
                    $token,
                    $usuario->id,
                ]
            );

            $correo = new RecuperacionMailable(token: $token);
            Mail::to($usuario->email)->send($correo);
        }

        $respuesta = new Respuestas(
            codigoHttp: 200,
            titulo: 'succes',
            mensaje: 'Se ha enviado un correo a la cuenta asosiada'
        );
        return response()->json($respuesta, $respuesta->codigoHttp);
    }

    protected function actualizarUsuario(string $password, string $rememberToken, $usuario)
    {
        $passwordCrtypt = bcrypt($password);
        $fecha = now();

        DB::update(
            query: "UPDATE usuarios 
                                SET password=?, email_verified_at=?, updated_at=?, remember_token=NULL
                                WHERE id=?",
            bindings: [
                $passwordCrtypt,
                $fecha,
                $fecha,
                $usuario->id,
            ]
        );

        //eliminar tokens de acceso
        DB::delete(
            query: "DELETE from personal_access_tokens WHERE usuario_id = ?",
            bindings: [
                $usuario->id
            ]
        );
    }
}
