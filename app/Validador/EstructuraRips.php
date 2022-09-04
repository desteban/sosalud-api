<?php

namespace App\Validador;

class EstructuraRips
{

    public static function ValidarRips(array $datosValidar = [], string $nombreCarpeta = ''): array
    {
        $rutaLeer = '/var/www/html/sosalud/public/TMPs' . $nombreCarpeta;
        $logErrores = array();

        if (!empty($datosValidar) && is_dir($rutaLeer))
        {

            $listadoRips = array(
                'AC',
                'AF',
                'AH',
                'AM',
                'AN',
                'AP',
                'AT',
                'AU',
                'CT',
                'US'
            );

            //recorrer el arreglo con los datos para validar que cuente con los archivos RIPS necesarios
            foreach ($datosValidar as $rips)
            {

                //obtener los 2 primeros caracteres del nombre del archivo
                $nombreRips = substr($rips, 0, 2);

                if (!array_search($nombreRips, $listadoRips))
                {
                    array_push($logErrores, "Archivo $nombreRips no se encuentra");
                }
            }
        }

        if (empty($datosValidar))
        {
            array_push($logErrores, "No se encontraron archivos validos dentro del archivo seleccionado");
        }

        if (!is_dir($rutaLeer))
        {
            array_push($logErrores, "No se ha encontrado el archivo");
        }

        return $logErrores;
    }
}
