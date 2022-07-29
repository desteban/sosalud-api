<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

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
        /**
         * Validar la informacion
         * max es el peso en kilobytes 1Mb = 1024kb
         */
        $request->validate([
            'archivo'   =>  ['required', 'mimes:rar,zip', 'max:5158']
        ]);

        if ($request->hasFile('archivo')) {

            //obtenermos el archivo enviado
            $archivo = $request->file('archivo');

            //establecemos un nombre para guardar el archivo
            $nombre = 'tmp_' . time();

            $this->respuesta = $this->extraerZip($archivo, $nombre);
        }

        return response()->json($this->respuesta, $this->respuesta['code']);
    }

    public function extraerZip($archivo, $nombreArchivo): array
    {
        $respuesta = [
            'code'  =>  201,
            'status' => 'Created'
        ];

        $zip = new ZipArchive;

        if ($zip->open($archivo)) {
            $archivoDescomprimido = $zip->extractTo('TMPs\\' . $nombreArchivo);
            echo $archivoDescomprimido;
            $zip->close();
        }

        if (!$zip->open($archivo)) {
            $respuesta['code'] = 400;
            $respuesta['status'] = 'Error';
        }

        return $respuesta;
    }
}
