<?php

namespace App\Models\RIPS;

/**
 * Archivo de usuarios
 */

class US extends RIPS implements IRips
{

    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoEntidadAdministradora = '';
    public $tipoUsuario = 0;
    public string $primerApellido = '';
    public string $segundoApellido = '';
    public string $primerNombre = '';
    public string $segundoNombre = '';
    public $edad = 0;
    public string $medidaEdad = '';
    public string $genero = '';
    public string $codigoDepartamento = '';
    public string $codigoMunicipio = '';
    public string $zona = '';
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
        return 'US';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'tipoIdentificacion,' .
            'identificacion,' .
            'codigoEapb,' .
            'tipoUsuario,' .
            'primerApellido,' .
            'segundoApellido,' .
            'primerNombre,' .
            'segundoNombre,' .
            'edad,' .
            'medidaEdad,' .
            'genero,' .
            'codigoDepartamento,' .
            'codigoMunicipio,' .
            'zona';
    }
}
