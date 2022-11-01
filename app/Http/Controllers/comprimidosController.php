<?php

namespace App\Http\Controllers;

use App\Exports\LogEstructura;
use App\Models\Respuestas;
use App\Models\TipoRIPS;
use App\Util\ArchivosUtil;
use App\Validador\EstructuraRips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class comprimidosController extends Controller
{
    protected $rutaRIPS = __DIR__ . "/../../../public/TMPs/";
    protected array $logErrores = [];

    /**
     * *funcion invocada al subir un archivo comprimido al sistema
     * @param Request
     */
    public function crearRIPS(Request $request)
    {
        $respuesta = new Respuestas;
        /**
         * Validar la informacion de la peticion
         * * max es el peso en kilobytes 1Mb = 1024kb
         */

        $validacion = Validator::make(
            data: $request->all(),
            rules: [
                'archivo'   =>  ['required', 'mimes:rar,zip', 'max:5158'],
                // 'tipoUsuario' => ['required'],
                // 'tipoContrato' => ['required'],
            ],
            messages: [
                'archivo.required' => 'Es necesario seleccionar un archivo',
                'archivo.mimes' => 'La extencion del archivo debe ser .rar o .zip',
                'archivo.max' => 'El archivo debe pesar menos de 5Mb',
                'tipoUsuario' => 'El tipo de usuario es obligatorio',
                'tipoContrato' => 'El tipo de contrato es necesario',
            ]
        );

        if ($validacion->fails())
        {
            $respuesta->cambiarRespuesta(
                codigoHttp: 400,
                titulo: 'Mala peticion',
                mensaje: "recuerde que el archivo subido sea valido",
                data: $validacion->getMessageBag(),
            );

            return response()->json($respuesta, $respuesta->codigoHttp);
        }

        if ($request->hasFile('archivo'))
        {
            $respuesta = $this->extraerArchivo($request);
        }


        //validar que se ha descomprimido el archivo
        if ($respuesta->codigoHttp == 201)
        {

            $nombreCarpeta = $respuesta->data;
            $respuesta = $this->validarEstructura($nombreCarpeta);

            if ($respuesta->codigoHttp == 200)
            {
                $rips = $this->guardarDB($nombreCarpeta);

                //realizar la validacion de contenido
                $errorContenido = $this->validadorContenido($rips, $nombreCarpeta);
                $respuesta->data = [
                    'estadoContenido' => $errorContenido,
                ];
            }

            //generar log de errores si la validacion de esctructura falla
            if ($respuesta->codigoHttp != 200)
            {
                return (new LogEstructura($respuesta->data))->download('Log.xlsx');
            }
        }


        return response()->json($respuesta, $respuesta->codigoHttp);
    }


    /**
     * @param request
     * @return Respuestas donde se especifica el estado de la tarea
     */
    protected function extraerArchivo(Request $request): Respuestas
    {
        $respuesta = new Respuestas(
            500,
            'cod-ex01',
            'Algo ha salido al momento de manipular el archivo seleccionado'
        );

        //obtenermos el archivo enviado
        $archivo = $request->file('archivo');

        //establecemos un nombre para guardar el archivo
        $nombre = time() . rand();

        $extraerArchivo = ArchivosUtil::extraerArchivosComprimidos($archivo, $nombre);

        if ($extraerArchivo)
        {
            $respuesta->cambiarRespuesta(201, 'Creado', 'El archivo se ha descomprimido con exito', $nombre);
        }

        return $respuesta;
    }

    protected function guardarDB(string $nombreCarpeta): array
    {
        $listadoRips = [];
        $rutaAPP = env('APP_DIR');
        $contenidoCarpetaTemporal = ArchivosUtil::obtenerContenidoDirectorio("$rutaAPP/public/TMPs/$nombreCarpeta");

        $queryLog = "CREATE TABLE IF NOT EXISTS tmp_logs_error_$nombreCarpeta(
            contenido TEXT NOT NULL,
            tipo VARCHAR(2) NOT NULL COMMENT 'El tipo de RIPS'
        );";
        DB::statement($queryLog, []);

        foreach ($contenidoCarpetaTemporal as $nombreArchivo)
        {
            $tipoRips = substr($nombreArchivo, 0, 2);
            $rips = TipoRIPS::escojerRips($tipoRips);
            array_push($listadoRips, $rips);

            if (!is_null($rips))
            {
                $rips->crearTablas($nombreCarpeta);

                $archivo = file("$rutaAPP/public/TMPs/$nombreCarpeta/$nombreArchivo");
                $rips->subirDB($archivo);
            }
        }

        return $listadoRips;
    }

    /**
     * *Esta funcion elimina los saltos de linea y espacio al momento de leer un RIPS
     * @param lineaRIPS string con la linea que deseamos limpiar
     * @return array separando el string por comas (,)
     */
    protected function limpiarRIPS(string $lineaRIPS): array
    {
        //eliminar saltos de linea y espacios
        $registro = str_replace(array("\r\n", "\n", "\r", ' '), '', $lineaRIPS);
        return explode(',', $registro);
    }

    /**
     * @return Respuestas donde data contiene el log de errores de la validacion de estructura
     */
    protected function validarEstructura(string $nombreCarpetaTemporal): Respuestas
    {
        $rutaAPP = env('APP_DIR');
        $respuesta = new Respuestas();
        $direccionCarpetaTemporal = "$rutaAPP/public/TMPs/$nombreCarpetaTemporal";
        $contenidoCarpetaTemporal = ArchivosUtil::obtenerContenidoDirectorio($direccionCarpetaTemporal);

        $estadoValidacion =  EstructuraRips::ValidarRips($nombreCarpetaTemporal, $contenidoCarpetaTemporal);

        if (!empty($estadoValidacion))
        {
            $respuesta->cambiarRespuesta(
                400,
                'cod-VEs',
                'Se presentaron errores en la validacion del archivo',
                $estadoValidacion
            );
        }

        return $respuesta;
    }

    protected function validadorContenido(array $listadoRips = [], string $nombreCarpeta = ''): bool
    {
        $errores = [];

        foreach ($listadoRips as $rips)
        {
            $rips->auditar();
        }
        $errores = DB::select(query: "SELECT * FROM tmp_logs_error_$nombreCarpeta");

        return !empty($errores);
    }
}
