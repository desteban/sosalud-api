<?php

namespace App\Models\RIPS;

/**
 * Archivo de urgencia con observación
 */

class AU extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaIngreso = '';
    public string $horaIngreso = '';
    public string $numeroAutorizacion = '';
    public string $causaExterna = '';
    public string $diagnostico = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public $referencia = 0;
    public string $estadoSalida = '';
    public string $CausaMuerte = '';
    public string $fechaSalida = '';
    public string $HoraSalida = '';
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

        $cantidadAtributos = 17;
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
        return 'AU';
    }
}
