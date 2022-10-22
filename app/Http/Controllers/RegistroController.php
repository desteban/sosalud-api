<?php

namespace App\Http\Controllers;

use App\Mail\RegistroMailable;
use App\Models\Respuestas;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function registrarUsuario(Request $request)
    {
        $respuesta = new Respuestas(201, 'Creado', 'Usuario registrado exitosamente');

        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'nombre' => 'required|alpha_num',
                'nombreUsuario' => 'required|unique:usuarios,nombreUsuario|alpha_num',
                'email' => 'required|unique:usuarios,email',
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
            'name' => $request->input('name'),
            'nombreUsuario' => $request->input('nombreUsuario'),
            'password' => $token,
        ]);
        $usuario->remember_token = $token;
        $usuario->save();

        try
        {
            $correo = new RegistroMailable($usuario->name, $usuario->nombreUsuario, $token);

            Mail::to($usuario['email'])->send($correo);

            return response()->json($respuesta, $respuesta->codigoHttp);
        }
        catch (\Throwable $th)
        {
            $respuesta->cambiarRespuesta(500, 'Hubo un error en el servidor y la solicitud no pudo ser completada');
        }
    }

    public function cambioPassword(Request $reques)
    {
        $validacion = Validator::make(
            data: $reques->all(),
            rules: [
                'password' => 'required|min:8',
                'confirm' => 'required|same:password'
            ],
            messages: [
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe contar con almenos 8 caracteres',
                'confirm.same' => 'Las contraseñas deben coincidir'
            ]
        );
    }
}
