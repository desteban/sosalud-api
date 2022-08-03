<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class formularioController extends Controller
{
    private $rutaRIPS = __DIR__ . '/../../../public/TMPs/';
    protected $respuesta = [
        'status'    =>  'succes',
        'code'      =>  200,
        'message'   =>  'Todo ha salido bien',
        'data'      =>  []
    ];

    public function guardarArchivo(Request $request)
    {
        /**
         * Validar la informacion
         * max es el peso en kilobytes 1Mb = 1024kb
         */
        $request->validate([
            'archivo'   =>  ['required', 'mimes:rar,zip,7z', 'max:5158']
        ]);
        $contenidoRIPS = [];

        if ($request->hasFile('archivo')) {

            //obtenermos el archivo enviado
            $archivo = $request->file('archivo');

            //dividir el nombre del archivo cuando se encuentre un punto (.)
            $nombreArchivo = explode(".", $archivo->getClientOriginalName());

            //establecemos un nombre para guardar el archivo
            $nombre = 'tmp_' . $nombreArchivo[0] . '_' . time();

            $extraer = $this->extraerZip($archivo, $nombre);

            if ($extraer) {
                $listaRIPS = $this->obtenerListaRIPS($nombre);
                $contenidoRIPS = $this->leerRIPS($listaRIPS, $nombre);

                $this->respuesta['data'] = $contenidoRIPS;
            }
        }

        return response()->json($this->respuesta, $this->respuesta['code']);
    }

    public function extraerZip($archivo, $nombreArchivo)
    {
        $respuestaExtraer = false;
        $rutaGuardar = 'TMPs/' . $nombreArchivo;

        if ($archivo->guessExtension() == "zip") {

            $zip = new ZipArchive;


            if ($zip->open($archivo, ZipArchive::CREATE)) {

                //descomprimir archivo y guardar los datos en la ruta especifica
                $archivoDescomprimido = $zip->extractTo($rutaGuardar);

                if ($archivoDescomprimido) {
                    $respuestaExtraer = true;
                }

                $zip->close();
            }
        }

        return $respuestaExtraer;
    }

    function obtenerListaRIPS($rutaArchivo = 'TMPs/null'): array
    {
        $rutaLeer = $this->rutaRIPS . "$rutaArchivo";
        $datos = [];

        if (is_dir($rutaLeer)) {

            $carpeta = opendir($rutaLeer);

            while ($archivo = readdir($carpeta)) {
                $txt = strpos($archivo, '.txt');

                if ($txt) {
                    array_push($datos, $archivo);
                }
            }
        }

        return $datos;
    }

    function leerRIPS($listaRIPS = [], $nombreCarpeta = ''): array
    {
        $rutaLeer = $this->rutaRIPS . "$nombreCarpeta";
        $arregloRIPS = [];

        if (sizeof($listaRIPS) > 0 && is_dir($rutaLeer)) {

            //recorrer los RIPS
            foreach ($listaRIPS as $nombreRIPS) {

                $tipoRIPS = substr($nombreRIPS, 0, 2);
                $ruta_RIPS = "$rutaLeer/$nombreRIPS";
                $contenido = [];

                // RIPS
                if (is_file($ruta_RIPS)) {

                    $RIPS = file($ruta_RIPS);
                    foreach ($RIPS as $linea) {
                        //eliminar el salto de linea
                        $registro = str_replace("\r\n", '', $linea);
                        array_push($contenido, explode(',', $registro));
                    }
                }

                //Agregar RIPS al arreglo que contiene la lista de los RIPS
                $arregloRIPS["$tipoRIPS"] = $contenido;
            }
        }
        return $arregloRIPS;
    }
}
