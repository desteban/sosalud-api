<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use App\Models\RIPS\RIPS;
use App\Models\TipoRIPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class comprimidosController extends Controller
{
    protected $rutaRIPS = __DIR__ . "/../../../public/TMPs/";

    public function crearRIPS(Request $request)
    {
        $respuesta = new Respuestas;
        /**
         * Validar la informacion de la peticion
         * * max es el peso en kilobytes 1Mb = 1024kb
         */
        $request->validate([
            'archivo'   =>  ['required', 'mimes:rar,zip', 'max:5158']
        ]);

        if ($request->hasFile('archivo'))
        {

            $respuesta = $this->manipularArchivoComprimido($request);
        }
        //validar que se ha descomprimido el archivo
        if ($respuesta->codigoHttp == 201)
        {

            $nombreCarpeta = $respuesta->data;
            $respuesta = $this->manipularCarpetaRIPS($nombreCarpeta);
        }


        return response()->json($respuesta, $respuesta->codigoHttp);
    }

    public function manipularArchivoComprimido(Request $request): Respuestas
    {
        $respuesta = new Respuestas(500, 'Internal Server Error', 'Algo ha salido al momento de manipular el archivo seleccionado');

        //obtenermos el archivo enviado
        $archivo = $request->file('archivo');

        //dividir el nombre del archivo cuando se encuentre un punto (.)
        $nombreArchivo = explode(".", $archivo->getClientOriginalName());

        //establecemos un nombre para guardar el archivo
        $nombre = 'tmp_' . $nombreArchivo[0] . '_' . time();

        $extraerArchivo = Archivos::extraerArchivosComprimidos($archivo, $nombre);

        if ($extraerArchivo)
        {
            $respuesta->cambiarRespuesta(201, 'Creado', 'El archivo se ha descomprimido con exito', $nombre);
        }

        return $respuesta;
    }

    function manipularCarpetaRIPS($nombreCarpeta): Respuestas
    {
        $respuesta = new Respuestas(400, 'Bad requesat', 'No se encontraron los archivos necesarios');

        try
        {
            $rutaLeer = $this->rutaRIPS . "$nombreCarpeta";
            $listadoRIPS = Archivos::obtenerContenidoDirectorio($rutaLeer);

            if (sizeof($listadoRIPS) > 0)
            {
                return $this->leerRIPS($listadoRIPS, $nombreCarpeta);
            }
        }
        catch (\Throwable $th)
        {
            $respuesta->cambiarRespuesta(500, 'Internal Server Error', 'Algo ha salido mal');
        }

        return $respuesta;
    }

    function leerRIPS($listaRIPS = [], $nombreCarpeta = ''): Respuestas
    {
        $respuesta = new Respuestas(201, 'Created', 'Datos guardados exitosamente');
        $rutaLeer = $this->rutaRIPS . "$nombreCarpeta";

        //validar que la carpeta cuente con RIPS
        if (sizeof($listaRIPS) > 0)
        {

            //recorrer listado de RIPS
            foreach ($listaRIPS as $nombreDocumentoRIPS)
            {

                //obtener el tipo del RIPS
                $tipoRIPS = substr($nombreDocumentoRIPS, 0, 2);
                $ruta_RIPS = "$rutaLeer/$nombreDocumentoRIPS";

                //obtener el contenido del documento
                $contenidoRips = $this->obtenerRips($ruta_RIPS, $tipoRIPS);

                //subir datos a la base de datos
                foreach ($contenidoRips as $rips)
                {
                    try
                    {
                        $rips->subirDB();
                    }
                    catch (\Throwable $th)
                    {
                        $respuesta->cambiarRespuesta(500, 'Internal Server Error', 'Algo ha salido mal al momento de subir el RIPS a la base de datos');
                    }
                }
            }

            return $respuesta;
        }

        if (sizeof($listaRIPS) <= 0)
        {
            $respuesta->cambiarRespuesta(400, 'Bad Request', 'La carpeta no cuenta con archivos dentro');

            return $respuesta;
        }
    }

    // *Esta funcion alimina los saltos de linea y espacio al momento de leer un RIPS
    function limpiarRIPS(string $lineaRIPS): array
    {
        //eliminar saltos de linea y espacios
        $registro = str_replace("\r\n", '', $lineaRIPS);
        $registro = str_replace(' ', '', $registro);
        return explode(',', $registro);
    }

    // *retorna un arreglo con los objetos RIPS necesarios creados
    function obtenerRips(string $rutaRIPS, string $tipoRIPS): array
    {
        $RIPS = array();

        if (is_file($rutaRIPS))
        {

            $documentoRIPS = file($rutaRIPS);
            foreach ($documentoRIPS as $linea)
            {

                $registroRIPS = $this->limpiarRIPS($linea);
                $tipo_RIPS = new TipoRIPS($tipoRIPS, $registroRIPS);
                array_push($RIPS, $tipo_RIPS->getTipoRips());
            }
        }

        return $RIPS;
    }
}
