<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use Illuminate\Http\Request;
use ZipArchive;

class comprimidosController extends Controller
{
    private $rutaRIPS = __DIR__ . '\\..\\..\\..\\public\\TMPs\\';
    private $fechasRIPS = ['CT', 'AF', 'AC', 'AP', 'AU' . 'AH', 'AN', 'AT'];

    public function crearRIPS(Request $request)
    {
        $respuesta = new Respuestas;
        /**
         * Validar la informacion
         * max es el peso en kilobytes 1Mb = 1024kb
         */
        $request->validate([
            'archivo'   =>  ['required', 'mimes:rar,zip,7z', 'max:5158']
        ]);

        if ($request->hasFile('archivo'))
        {

            $respuesta = $this->manipularArchivoComprimido($request);
        }


        return response()->json($respuesta, $respuesta->codigoHttp);
    }

    public function manipularArchivoComprimido(Request $request): Respuestas
    {
        $respuesta = new Respuestas;

        //obtenermos el archivo enviado
        $archivo = $request->file('archivo');

        //dividir el nombre del archivo cuando se encuentre un punto (.)
        $nombreArchivo = explode(".", $archivo->getClientOriginalName());

        //establecemos un nombre para guardar el archivo
        $nombre = 'tmp_' . $nombreArchivo[0] . '_' . time();

        $extraerArchivo = $this->extraerZip($archivo, $nombre);

        if ($extraerArchivo)
        {
            $respuesta->mensaje = 'El archivo se ha descomprimido con éxito';
        }

        return $respuesta;
    }

    public function extraerZip($archivo, $nombreArchivo): bool
    {
        $respuestaExtraer = false;
        $rutaGuardar = 'TMPs/' . $nombreArchivo;


        if ($archivo->guessExtension() == "zip")
        {

            $zip = new ZipArchive;

            if ($zip->open($archivo, ZipArchive::CREATE))
            {

                //descomprimir archivo y guardar los datos en la ruta especifica
                $archivoDescomprimido = $zip->extractTo($rutaGuardar);

                if ($archivoDescomprimido)
                {
                    $respuestaExtraer = true;
                }

                $zip->close();
            }
        }

        return $respuestaExtraer;
    }
}
