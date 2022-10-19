<?php

namespace App\Http\Controllers;

use App\Mail\RegistroMailable;
use App\Models\Respuestas;
use App\Models\Usuarios;
use App\Util\Token;
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

        $validacion = Validator::make($request->all(), [
            'name' => 'required',
            'nombreUsuario' => 'required|unique:usuarios,nombreUsuario',
            'email' => 'required|unique:usuarios,email'
        ]);

        if ($validacion->fails())
        {
            $erroresValidacion = $validacion->failed();
            $errores = [];

            if ($erroresValidacion['email']['Unique'])
            {
                array_push($errores, 'Correo ya registrado');
            }


            $respuesta->cambiarRespuesta(400, 'Mala peticion', 'Porfavor valide la informacion', $errores);
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
}
