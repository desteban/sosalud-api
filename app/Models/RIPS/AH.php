<?php

namespace App\Models\RIPS;

/**
 * Archivo de hospitalización
 */

class AH implements RIPS
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoViaIngreso = '';
    public string $fechaIngreso = '';
    public string $horaIngreso = '';
    public int $numeroAutorizacion = 0;
    public string $codigoCausaExterna = '';
    public string $diagnoticoIngreso = '';
    public string $diagnosticoEgreso = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public string $codigoCompicacion = '';
    public string $estadoSalida = '';
    public string $causaMuerte = '';
    public string $fechaEgreso = '';
    public string $horaEgreso = '';
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