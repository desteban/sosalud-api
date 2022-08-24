<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 *  Archivo de transacciones
 */

class AF extends RIPS implements IRips
{

    public string $codigoIps = '';
    public string $nombreIps = '';
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

    public static function obtenerColumnasDB(): string
    {
        return 'codigoIps,' .
            'nombreIps, ' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroFactura,' .
            'fechaFactura,' .
            'fechaInicio,' .
            'fechaFinal,' .
            'codigoEapb,' .
            'nombreEapb,' .
            'numeroContrato,' .
            'planBeneficios,' .
            'numeroPoliza,' .
            'copago,' .
            'valorComision,' .
            'valorDescuentos,' .
            'valorFactura';
    }
}
