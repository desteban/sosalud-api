<?php

namespace App\Models\RIPS;

/**
 * Archivo de control
 */

class CT extends RIPS implements IRips
{

    public string $codIPS = '';
    public string $fechaRemision = '';
    public string $codigoArchivo = '';
    public string $totalRegistros = '';
    protected int $id = 0;

    public function obtenerDatos(): string
    {
        $datos = '';

        foreach ($this as $clave => $valor)
        {
            $type = gettype($this->{$clave});
            $datos .= $this->typeToString($type, $valor) . ',';
        }

        $datos = rtrim($datos, ',');
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

    public static function obtenerColumnasDB(): string
    {
        return 'codigoIps,' .
            'fechaRemision,' .
            'codigoArchivo,' .
            'totalRegistros';
    }
}
