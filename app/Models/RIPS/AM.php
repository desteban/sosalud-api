<?php

namespace App\Models\RIPS;

/**
 * 
 * Archivo de medicamentos
 */

class AM implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public $numeroAutorizacion = 0;
    public string $codigoMedicamento = '';
    public $tipoMedicamento = 0;
    public string $nombreGenerico = '';
    public string $formaFarmaceutica = '';
    public string $concentracionMedicamento = '';
    public string $unidadMedida = '';
    public string $numeroUnidad = '';
    public string $valorUnitarios = '';
    public string $valorTotal = '';
    protected int $id;

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

        $cantidadAtributos = 14;
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
        return 'AM';
    }
}
