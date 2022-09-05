<?php

namespace App\Validador;

class EstructuraRips
{

    /**
     * * si llega a este punto ya se ha generado la carpeta temporal respectiva
     * @param datosValidar arreglo con el contenido (.txt) de una carpeta
     */
    public static function ValidarRips(array $datosValidar = []): array
    {
        $logErrores = array();

        $filtoCT = EstructuraRips::filtrarCT($datosValidar);

        if (empty($filtoCT))
        {
            array_push($logErrores, "No se ha encontrado el Archivo CT");
        }

        if (!empty($datosValidar))
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

                if (!in_array($nombreRips, $listadoRips))
                {
                    array_push($logErrores, "El archivo ($rips) no es un archivo valido");
                }
            }
        }

        if (empty($datosValidar))
        {
            array_push($logErrores, "No se encontraron archivos validos dentro del archivo seleccionado");
        }

        return $logErrores;
    }

    public static function filtrarCT(array $listado): array
    {

        return array_filter($listado, function ($item)
        {
            if (preg_match('/^CT\d*.txt/', $item))
            {
                return $item;
            }
        });
    }
}
