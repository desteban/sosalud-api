<?php

namespace App\Models\RIPS;

/**
 *  Archivo de transacciones
 */

class AF extends RIPS implements IRips
{

    public string $codigoIPS = '';
    public string $nombreIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $numeroFactura = '';
    public string $fechaFactura = '';
    public string $fechaInicio = '';
    public string $fechaFinal = '';
    public string $codigoEntidadAdministradora = '';
    public string $nombreEntidadAdministradora = '';
    public string $numeroContrato = '';
    public string $planBeneficios = '';
    public string $numeroPoliza = '';
    public $copago = 0;
    public $valorComision = 0;
    public $valorDescuento = 0;
    public $valorFactura = 0;
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
        return 'AF';
    }
}
