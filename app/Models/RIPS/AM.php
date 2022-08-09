<?php

namespace App\Models\RIPS;

/**
 * 
 * Archivo de medicamentos
 */

class AM implements RIPS
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public int $numeroAutorizacion = 0;
    public string $codigoMedicamento = '';
    public int $tipoMedicamento = 0;
    public string $nombreGenerico = '';
    public string $formaFarmaceutica = '';
    public string $concentracionMedicamento = '';
    public string $unidadMedida = '';
    public string $numeroUnidad = '';
    public string $valorUnitarios = '';
    public string $valorTotal = '';
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
