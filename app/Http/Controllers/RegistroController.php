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

        $usuario = $request->all();
        $usuario['password'] = md5($request->input('email'));
        unset($usuario['_token']);

        try
        {
            Usuarios::create($usuario);

            $correo = new RegistroMailable($usuario['name'], $usuario['password'], $usuario['nombreUsuario']);

            Mail::to($usuario['email'])->send($correo);

            return response()->json($respuesta, $respuesta->codigoHttp);
        }
        catch (\Throwable $th)
        {
            $respuesta->cambiarRespuesta(500, 'Hubo un error en el servidor y la solicitud no pudo ser completada');
        }
    }
}
