<?php

namespace App\Http\Controllers;

use App\Models\TipoRIPS;
use Illuminate\Http\Request;
use ZipArchive;

class formularioController extends Controller
{
    private $rutaRIPS = __DIR__ . '\\..\\..\\..\\public\\TMPs\\';
    private $fechasRIPS = ['CT', 'AF', 'AC', 'AP', 'AU' . 'AH', 'AN', 'AT'];
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

        if ($request->hasFile('archivo'))
        {

            //obtenermos el archivo enviado
            $archivo = $request->file('archivo');

            //dividir el nombre del archivo cuando se encuentre un punto (.)
            $nombreArchivo = explode(".", $archivo->getClientOriginalName());

            //establecemos un nombre para guardar el archivo
            $nombre = 'tmp_' . $nombreArchivo[0] . '_' . time();

            $extraer = $this->extraerZip($archivo, $nombre);

            if ($extraer)
            {
                $listaRIPS = $this->obtenerListaRIPS($nombre);
                $contenidoRIPS = $this->leerRIPS($listaRIPS, $nombre);

                // $this->respuesta['data'] = $contenidoRIPS;
                $cambiosFechaRIPS = $this->validarFechaRips($contenidoRIPS);
                dd($cambiosFechaRIPS);
            }
        }

        return response()->json($this->respuesta, $this->respuesta['code']);
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

    function obtenerListaRIPS($rutaArchivo = 'TMPs/null'): array
    {
        $rutaLeer = $this->rutaRIPS . "$rutaArchivo";
        $datos = [];

        if (is_dir($rutaLeer))
        {

            $carpeta = opendir($rutaLeer);

            while ($archivo = readdir($carpeta))
            {
                $txt = strpos($archivo, '.txt');

                if ($txt)
                {
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

        if (sizeof($listaRIPS) > 0 && is_dir($rutaLeer))
        {

            //recorrer RIPS
            foreach ($listaRIPS as $nombreRIPS)
            {

                //obtener el tipo del RIPS
                $tipoRIPS = substr($nombreRIPS, 0, 2);
                $ruta_RIPS = "$rutaLeer\\$nombreRIPS";


                if (is_file($ruta_RIPS))
                {

                    $RIPS = file($ruta_RIPS);
                    foreach ($RIPS as $linea)
                    {

                        $registro = $this->obtenerRIPS($linea);
                        $contenidoRIPS = new TipoRIPS($tipoRIPS, $registro);
                        array_push($arregloRIPS, $contenidoRIPS);
                    }
                }
            }
        }

        return $arregloRIPS;
    }

    public function validarFechaRips(array $RIPS = []): array
    {

        foreach ($RIPS as $item)
        {
            $contieneFecha = array_search($item->getTipoRips(), $this->fechasRIPS);

            if ($contieneFecha)
            {
                $contenidoRIPS = $item->contenidoRips;

                array_map(function ($contenido)
                {
                    if ($this->esFecha($contenido))
                    {
                        $fechaCambiada = $this->cambiarFormatoFecha($contenido);

                        $contenido = $fechaCambiada;
                    }
                }, $contenidoRIPS);
            }
        }

        return $RIPS;
    }

    function comentar($data)
    {
        dd($data);
    }

    function cambiarFormatoFecha(string $fecha = null)
    {
        $buscarFecha = strpos($fecha, '/');
        if ($fecha && $buscarFecha)
        {
            return date_format(date_create_from_format('d/m/Y', $fecha), 'Y/m/d');
        }

        return null;
    }

    public function esFecha(string $fecha = null)
    {
        $fechaRegex = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/';
        return preg_match($fechaRegex, $fecha);
    }

    function obtenerRIPS(string $lineaRIPS): array
    {
        //eliminar saltos de linea y espacios
        $registro = str_replace("\r\n", '', $lineaRIPS);
        $registro = str_replace(' ', '', $registro);
        return explode(',', $registro);
    }
}
