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

        $usuario = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => md5($request->input('email'))
        ];

        try
        {
            $usuarioDb = Usuarios::create($usuario);

            $correo = new RegistroMailable($usuario['name'], $usuario['password']);

            Mail::to($usuario['email'])->send($correo);

            return response()->json($respuesta, $respuesta->codigoHttp);
        }
        catch (\Throwable $th)
        {
            $respuesta->cambiarRespuesta(500, 'Hubo un error en el servidor y la solicitud no pudo ser completada');
        }
    }
}
