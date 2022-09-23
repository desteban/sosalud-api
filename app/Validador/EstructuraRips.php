<?php

namespace App\Validador;

use App\Models\TipoRIPS;

class EstructuraRips
{

    /**
     * * si llega a este punto ya se ha generado la carpeta temporal respectiva
     * @param datosValidar arreglo con el nombre de los archivos (.txt) de una carpeta
     * @param nombreCarpeta nombre de la carpeta del archivo descomprimido
     * @return array con el log de errores encontrados en la validadion de la estructura del RIPS
     */
    public static function ValidarRips(string $nombreCarpeta, array $datosValidar = []): array
    {
        $logErrores = array();
        $listadoRips = array();

        $filtroCT = EstructuraRips::filtrarCT($datosValidar);

        // valida si se encuentra un archivo CT
        if (empty($filtroCT))
        {
            array_push($logErrores, "No se ha encontrado el Archivo CT");
        }

        // valida si se encuentran varios archivos CT
        if (sizeof($filtroCT) > 1)
        {
            array_push($logErrores, 'Se encontraron varios archivos CT');
        }

        if (empty($datosValidar))
        {
            array_push($logErrores, "No se encontraron archivos validos dentro del archivo seleccionado");
        }

        if (!empty($datosValidar) && sizeof($filtroCT) == 1)
        {

            $listadoRipsValidar = TipoRIPS::listadoRips(false);

            //recorrer el arreglo con los datos para validar que cuente con los archivos RIPS necesarios
            foreach ($datosValidar as $rips)
            {

                //obtener los 2 primeros caracteres del nombre del archivo
                $nombreRips = substr($rips, 0, 2);

                if (!in_array($nombreRips, $listadoRipsValidar))
                {

                    array_push($logErrores, "El archivo ($rips) no es un archivo valido");
                }
                else
                {
                    //agregar nombre al listado de Rips para validar el archivo CT
                    array_push($listadoRips, $nombreRips);
                }
            }

            //validar informacion del archivo CT
            $validarCT = new ValidadorCT($nombreCarpeta, $filtroCT[0], $listadoRips);
            $erroresCT = $validarCT->validar();

            $logErrores = array_merge($logErrores, $erroresCT);
        }

        return $logErrores;
    }

    /**
     * @param listado arreglo con todos los archivos (.txt) dentro de la carpeta descomprimida
     * @return array arreglo con el nombre completo del archivo CT
     */
    public static function filtrarCT(array $listado): array
    {

        $filtroCT = array();

        foreach ($listado as $value)
        {
            if (preg_match('/(CT)\w+.txt/', $value))
            {
                array_push($filtroCT, $value);
            }
        }

        return $filtroCT;
    }
}
