<?php

namespace App\Models\RIPS;

/**
 * Archivo de usuarios
 */

class US implements RIPS
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

        $cantidadAtributos = 14;
        if (sizeof($datos) == $cantidadAtributos)
        {

            $obj = (array) $this;
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
}
