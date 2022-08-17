<?php

namespace App\Models\RIPS;

/**
 * Archivo de control
 */

class CT implements IRips
{

    public string $codIPS = '';
    public string $fechaRemision = '';
    public string $codigoArchivo = '';
    public string $totalRegistros = '';
    private int $id = 0;

    public function subirDB(array $datos)
    {
        //codigo para subir rips a la db
        echo 'Subiendo a db...' . $datos[0]->tipoRIPS() . "\n";
    }

    public function obtenerDatos(): array
    {
        $datos = [];

        foreach ($this as $clave => $valor)
        {
            array_push($datos, $valor);
        }

        return $datos;
    }

    public function agregarDatos(array $datos)
    {

        $cantidadAtributos = 4;
        if (sizeof($datos) == $cantidadAtributos)
        {

            $indice = 0;

            foreach ($this as $clave => $valor)
            {
                if ($indice < $cantidadAtributos)
                {
                    $this->{$clave} = $datos[$indice];

                    $indice++;
                }
            }
        }
    }

    public function tipoRIPS(): string
    {
        return 'CT';
    }
}
