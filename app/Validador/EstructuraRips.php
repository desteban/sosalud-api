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

        return $logErrores;
    }
}
