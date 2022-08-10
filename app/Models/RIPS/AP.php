<?php

namespace App\Models\RIPS;

/**
 * Archivo de procedimientos
 */

class AP implements RIPS
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaProcedimiento = '';
    public $numeroAutorizacion = 0;
    public string $codigoProcedimiento = '';
    public $ambitoProcedimiento = 0;
    public $finalidadProcedimiento = 0;
    public string $personalAtiende = '';
    public string $diagnostico = '';
    public string $diagnostico1 = '';
    public string $diagnosticoComplicacion = '';
    public $actoQuirurgico = 0;
    public $valorProcedimiento;
    protected int $id;

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

        $cantidadAtributos = 15;
        if (sizeof($datos) == $cantidadAtributos)
        {

            $obj = (array) $this;
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
}
