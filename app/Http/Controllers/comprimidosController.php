<?php

namespace App\Http\Controllers;

use App\Models\Respuestas;
use App\Models\RIPS\AC;
use App\Models\RIPS\IRips;
use App\Models\TipoRIPS;
use Illuminate\Http\Request;
use ZipArchive;

use function App\Models\RIPS\detectarRIPS;

class comprimidosController extends Controller
{
    protected $rutaRIPS = __DIR__ . "/../../../public/TMPs/";
    protected $fechasRIPS = ['CT', 'AF', 'AC', 'AP', 'AU' . 'AH', 'AN', 'AT'];

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
        //validar que se ha descomprimido el archivo
        if ($respuesta->codigoHttp == 201)
        {

            $nombreCarpeta = $respuesta->data;
            $RIPS = $this->manipularCarpetaRIPS($nombreCarpeta);
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
            $respuesta->cambiarRespuesta(201, 'Creado', 'El archivo se ha descomprimido con éxito', $nombre);
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

    function manipularCarpetaRIPS($nombreCarpeta = 'tmp_CT000221_1660013324')
    {
        $RIPS = [];

        $listadoRIPS = $this->obtenerListaRIPS($nombreCarpeta);
        $this->leerRIPS($listadoRIPS, $nombreCarpeta);

        return $RIPS;
    }

    public function obtenerListaRIPS($rutaArchivo = 'tmp_CT000221_1660013324'): array
    {
        $rutaLeer = $this->rutaRIPS . "$rutaArchivo";
        $datos = [];

        if (is_dir($rutaLeer))
        {

            $carpeta = opendir($rutaLeer);
            while ($archivo = readdir($carpeta))
            {
                //obtener todos los archivos con extencion .txt
                $txt = strpos($archivo, '.txt');

                if ($txt)
                {
                    array_push($datos, $archivo);
                }
            }
        }

        return $datos;
    }

    function leerRIPS($listaRIPS = [], $nombreCarpeta = '')
    {
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
                $RIPS = $this->obtenerRips($ruta_RIPS, $tipoRIPS);
            }
        }
    }

    function limpiarRIPS(string $lineaRIPS): array
    {
        //eliminar saltos de linea y espacios
        $registro = str_replace("\r\n", '', $lineaRIPS);
        $registro = str_replace(' ', '', $registro);
        return explode(',', $registro);
    }

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

        //subir datos a db
        $RIPS[0]->subirDB($RIPS);

        return $RIPS;
    }
}
