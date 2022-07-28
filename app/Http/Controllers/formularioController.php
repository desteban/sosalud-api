<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class formularioController extends Controller
{

    protected $respuesta = [
        'status'    =>  'succes',
        'code'      =>  200,
        'message'   =>  'Todo ha salido bien',
        'data'      =>  []
    ];

    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'archivo' => ['required']
        ]);

        if ($validador->fails()) {
            $this->respuesta['code'] = 400;
            $this->respuesta['data'] = $validador;
        }

        return response()->json($this->respuesta, $this->respuesta['code']);
    }

    public function guardarArchivo(Request $request)
    {
        //en max es el peso en kilobytes 1Mb = 1024kb
        $request->validate([
            'archivo'   =>  ['required', 'mimes:rar,zip', 'max:5158']
        ]);

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombre = 'tmp_' . time() . '.' . $archivo->guessExtension();
            $ruta = public_path('TMPs\\' . $nombre);

            if ($archivo->guessExtension() == 'rar' || $archivo->guessExtension() == 'zip') {
                copy($archivo, $ruta);
                $this->respuesta['code'] = 201;
                $this->respuesta['status'] = 'Created';
            }
        }

        return response()->json($this->respuesta, $this->respuesta['code']);
    }
}
