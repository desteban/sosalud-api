<?php

namespace App\Models\RIPS;

/**
 * . Archivo de transacciones
 */

class AF implements RIPS
{

    public string $codigoIPS = '';
    public string $nombreIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaFactura = '';
    public string $fechaInicio = '';
    public string $fechaFinal = '';
    public string $codigoEntidadAdministradora = '';
    public string $nombreEntidadAdministradora = '';
    public string $numeroContrato = '';
    public string $planBeneficios = '';
    public string $numeroPoliza = '';
    public float $copago = 0;
    public float $valorComision = 0;
    public float $valorDescuento = 0;
    public float $valorFactura = 0;
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
