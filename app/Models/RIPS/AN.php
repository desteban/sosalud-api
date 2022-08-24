<?php

namespace App\Models\RIPS;

/**
 * Archivo de recién nacidos
 */

class AN extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $Identificacion = '';
    public string $fechaNacimiento = '';
    public string $horaNacimiento = '';
    public $edadGestacion = 0;
    public string $controlPrenatal = '';
    public string $genero = '';
    public $peso = 0;
    public string $diagnostico = '';
    public string $diagnosticoMuerte = '';
    public string $fechaMuerte = '';
    public string $horaMuerte = '';
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
        return 'AN';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaNacimiento,' .
            'horaNacimiento,' .
            'edadGestacion,' .
            'controlPrenatal,' .
            'genero,' .
            'peso,' .
            'diagnostico,' .
            'diagnosticoMuerte,' .
            'fechaMuerte,' .
            'horaMuerte';
    }
}
