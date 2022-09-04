<?php

namespace App\Http\Controllers;

use ZipArchive;

class Archivos
{

    public static function extraerArchivosComprimidos($archivo, string $nombreArchivo = 'default', $rutaArchivo = null): bool
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

        if ($extencion == 'rar' && $rutaArchivo != null)
        {
            exec("unrar x /var/www/html/sosalud/storage/app/$rutaArchivo /var/www/html/sosalud/public/TMPs/$nombreArchivo/");
        }

        return $respuestaExtraer;
    }

    public static function eliminarArchivosTemporales()
    {
        exec("rm -r -f /var/www/html/sosalud/public/TMPs/* & rm -r -f /var/www/html/sosalud/storage/app/comprimidos/*");
    }

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
}
