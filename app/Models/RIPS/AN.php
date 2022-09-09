<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de recién nacidos
 */

class AN extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
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
    protected int $id;

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

    public function tipoRIPS(): string
    {
        return 'AN';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = explode(',', $this->obtenerColumnasDB());

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];

                if (Fechas::esFecha($datoGuardar))
                {
                    $datoGuardar = Fechas::cambiarFormatoFecha($datoGuardar);
                }

                $this->{"$atributos[$i]"} = $datoGuardar;
            }
        }
    }

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

    public function crearTablas(string $nombreTabla)
    {

        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AN_$nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaNacimiento date NOT NULL DEFAULT '0000-01-01',
            horaNacimiento time NOT NULL DEFAULT '00:00:00',
            edadGestacion tinyint NOT NULL DEFAULT '0',
            controlPrenatal enum('1','2') NOT NULL DEFAULT '1',
            genero enum('M','F') NOT NULL DEFAULT 'M',
            peso int NOT NULL DEFAULT '0',
            diagnostico varchar(4) NOT NULL DEFAULT '',
            diagnosticoMuerte varchar(4) DEFAULT NULL,
            fechaMuerte date DEFAULT NULL,
            horaMuerte time DEFAULT NULL,
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");
    }

    public function subirDB(array $datos = [])
    {

        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AN ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }
}
