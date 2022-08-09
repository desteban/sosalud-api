<?php

namespace App\Models\RIPS;

/**
 * Archivo de urgencia con observación
 */

class AU implements RIPS
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
    public int $referencia = 0;
    public string $estadoSalida = '';
    public string $CausaMuerte = '';
    public string $fechaSalida = '';
    public string $HoraSalida = '';
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