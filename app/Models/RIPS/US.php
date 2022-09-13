<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de usuarios
 */

class US extends RIPS implements IRips
{

    // codigoEntidadAdministradora = codigoEapb
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoEapb = '';
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
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'tipoIdentificacion,' .
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

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'US';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];

                $this->{"$atributos[$i]"} = $datoGuardar;
            }
        }
    }

    public function obtenerDatos(): array
    {
        $atributos = $this->obtenerColumnasDB(true);
        $salidaArray = array();

        foreach ($atributos as $clave)
        {
            $salidaArray["$clave"] = $this->{$clave};
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {

        $this->nombreTabla = $nombreTabla;

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

    public function subirDB(array $datos = []): bool
    {

        $values = array();

        foreach ($datos as $linea)
        {

            $lineaLimpia = str_replace(array("\r\n", "\r", "\n", " "), "", $linea);
            $datosArray = explode(',', $lineaLimpia);

            $this->agregarDatos($datosArray);
            array_push($values, $this->obtenerDatos(true));
        }

        try
        {
            return DB::table("tmp_US_$this->nombreTabla")->insert($values);
        }
        catch (\Throwable $th)
        {
            return false;
        }
    }
}
