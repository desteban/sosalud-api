<?php

namespace App\Http\Controllers;

use ZipArchive;

class Archivos
{

    public static function extraerArchivosComprimidos($archivo, string $nombreArchivo = 'default'): bool
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
