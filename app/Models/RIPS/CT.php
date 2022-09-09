<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de control
 */

class CT extends RIPS implements IRips
{

    public string $codigoIps = '';
    public string $fechaRemision = '';
    public string $codigoArchivo = '';
    public int $totalRegistros = 0;
    protected int $id = 0;
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(): string
    {
        return 'codigoIps,' .
            'fechaRemision,' .
            'codigoArchivo,' .
            'totalRegistros';
    }

    public function tipoRIPS(): string
    {
        return 'CT';
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
        $indice = 0;

        foreach ($this as $clave => $valor)
        {
            if ($indice < 4)
            {

                $type = gettype($this->{$clave});
                $datos .= $this->typeToString($type, $valor) . ',';
            }

            $indice++;
        }

        $datos = rtrim($datos, ',');
        return $datos;
    }

    public function crearTablas(string $nombreTabla)
    {
        $this->nombreTabla = $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_CT_$nombreTabla (
            codigoIps varchar(20) NOT NULL DEFAULT '',
            fechaRemision date NOT NULL DEFAULT '0000-01-01',
            codigoArchivo varchar(10) NOT NULL DEFAULT '',
            totalRegistros int NOT NULL DEFAULT '0',
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");
    }

    public function subirDB(array $datos = []): bool
    {

        $columnas = $this->obtenerColumnasDB();
        $values = '';

        foreach ($datos as $linea)
        {

            $lineaLimpia = str_replace(array("\r\n", "\r", "\n", " "), "", $linea);
            $datosArray = explode(',', $lineaLimpia);

            $this->agregarDatos($datosArray);
            $values .= '(' . $this->obtenerDatos() . '), ';
        }

        $values = rtrim($values, ', ');

        try
        {
            return DB::insert("INSERT INTO tmp_CT_$this->nombreTabla ($columnas) VALUES $values");
        }
        catch (\Throwable $th)
        {
            return false;
        }
    }
}
