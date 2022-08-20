<?php

namespace App\Models\RIPS;

/**
 * Archivo de otros servicios
 */

class AT extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public $numeroAutorizacion = 0;
    public $tipoServicio = 0;
    public string $codigoServicio = '';
    public string $nombreServicio = '';
    public string $cantidad = '';
    public $valorUnitario = 0;
    public $valorTotal = 0;
    protected int $id;

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

        $cantidadAtributos = 11;
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
        return 'AT';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroAutorizacion,' .
            'tipoServicio,' .
            'codigoServicio,' .
            'nombreServicio,' .
            'cantidad,' .
            'valorUnitario,' .
            'valorTotal';
    }
}
