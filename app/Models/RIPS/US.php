<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de usuarios
 */

class US extends RIPS implements IRips
{

    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoEntidadAdministradora = '';
    public int $tipoUsuario = 0;
    public string $primerApellido = '';
    public string $segundoApellido = '';
    public string $primerNombre = '';
    public string $segundoNombre = '';
    public int $edad = 0;
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
                    $this->{$clave} = $this->parceItem(gettype($this->{$clave}), $datos[$indice]);

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

    public function subirDB()
    {

        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_US ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }

    public function crearTablas(string $nombreTabla)
    {
        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_US_$nombreTabla (
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            codigoEapb varchar(6) NOT NULL DEFAULT '',
            tipoUsuario tinyint(1) NOT NULL DEFAULT '2',
            primerApellido varchar(30) NOT NULL DEFAULT '',
            segundoApellido varchar(30) DEFAULT NULL,
            primerNombre varchar(20) NOT NULL DEFAULT '',
            segundoNombre varchar(20) DEFAULT NULL,
            edad tinyint DEFAULT NULL,
            medidaEdad enum('1','2','3') DEFAULT NULL,
            genero enum('M','F') DEFAULT NULL,
            codigoDepartamento char(2) NOT NULL DEFAULT '',
            codigoMunicipio char(3) NOT NULL DEFAULT '',
            zona enum('U','R') NOT NULL DEFAULT 'U',
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");
    }
}
