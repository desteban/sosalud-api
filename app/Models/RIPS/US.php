<?php

namespace App\Models\RIPS;

/**
 * Archivo de usuarios
 */

class US implements IRips
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
        return 'US';
    }
}
