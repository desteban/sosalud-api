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
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
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

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AN';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

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

    public function subirDB(array $datos = []): bool
    {

        $values = array();

        foreach ($datos as $linea)
        {

            $lineaLimpia = str_replace(array("\r\n", "\r", "\n", " "), "", $linea);
            $datosArray = explode(',', $lineaLimpia);

            $this->datosDefecto();
            $this->agregarDatos($datosArray);
            array_push($values, $this->obtenerDatos(true));
        }

        try
        {
            return DB::table("tmp_AM_$this->nombreTabla")->insert($values);
        }
        catch (\Throwable $th)
        {
            return false;
        }
    }

    protected function datosDefecto(): void
    {
        $this->agregarDatos(array(
            '',
            '',
            '',
            '',
            '',
            '',
            0,
            '',
            '',
            0,
            '',
            '',
            '',
            '',
        ));
    }
}
