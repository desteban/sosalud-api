<?php

namespace App\Http\Controllers;

use ZipArchive;

use function PHPUnit\Framework\isNull;

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
}
