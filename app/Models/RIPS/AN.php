<?php

namespace App\Models\RIPS;

/**
 * Archivo de recién nacidos
 */

class AN implements RIPS
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $Identificacion = '';
    public string $fechaNacimiento = '';
    public string $horaNacimiento = '';
    public int $edadGestacion = 0;
    public string $controlPrenatal = '';
    public string $genero = '';
    public int $peso = 0;
    public string $diagnostico = '';
    public string $diagnosticoMuerte = '';
    public string $fechaMuerte = '';
    public string $horaMuerte = '';
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
