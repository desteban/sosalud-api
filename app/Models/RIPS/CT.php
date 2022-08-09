<?php

namespace App\Models\RIPS;

/**
 * Archivo de control
 */

class CT implements RIPS
{

    public string $codIPS = '';
    public string $fechaRemision = '';
    public string $codigoArchivo = '';
    public string $totalRegistros = '';
    private int $id;

    public function subirDB()
    {
        //codigo para subir rips a la db
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

        foreach ($this as $clave => $valor)
        {
            echo "$clave => $valor\n";
        }
    }
}
