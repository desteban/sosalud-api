<?php

namespace App\Http\Controllers;

use ZipArchive;

use function PHPUnit\Framework\isNull;

class Archivos
{

    public static function extraerArchivosComprimidos($archivo, string $nombreArchivo = 'default'): bool
    {

        $respuestaExtraer = false;
        $rutaGuardar = 'TMPs/' . $nombreArchivo;
        $extencion = $archivo->guessExtension();

        if ($extencion == "zip")
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

        if ($extencion == 'rar')
        {
            $rutaArchivo = Archivos::guardarArchivoServidor($archivo, 'comprimidos');
            if (!empty($rutaArchivo))
            {
                exec("unrar x /var/www/html/sosalud/storage/app/$rutaArchivo /var/www/html/sosalud/public/TMPs/$nombreArchivo/");
            }
        }

        // validar si se ha extraido el archivo exitosamente
        if (is_dir("/var/www/html/sosalud/public/TMPs/$nombreArchivo/"))
        {
            $respuestaExtraer = true;
        }

        return $respuestaExtraer;
    }

    public static function eliminarArchivosTemporales()
    {
        $direccionApp = env('APP_DIR');
        exec("rm -rf $direccionApp/public/TMPs/* & rm -rf $direccionApp/storage/app/comprimidos/*");
    }

    /**
     * @param direccionCarpeta ruta de la carpeta a buscar
     * @param extencion para filtrar por extencion de archivos que desea retornar en la funcion
     */
    public static function obtenerContenidoDirectorio(string $direccionCarpeta, string $extencion = '.txt'): array
    {

        $datos = array();

        if (is_dir($direccionCarpeta))
        {

            $carpeta = opendir($direccionCarpeta);
            while ($archivo = readdir($carpeta))
            {
                //obtener todos los archivos con extencion .txt
                $archivoFiltrado = strpos($archivo, $extencion);
                if ($archivoFiltrado)
                {
                    array_push($datos, $archivo);
                }
            }
        }

        return $datos;
    }

    /**
     * @param archivo 
     * @param ubicacion la ruta donde se guardara el archivo (/storage/app)
     */
    public static function guardarArchivoServidor($archivo, string $ubicacion): string
    {
        try
        {
            return $archivo->store($ubicacion);
        }
        catch (\Throwable $th)
        {
            return '';
        }
    }
}
