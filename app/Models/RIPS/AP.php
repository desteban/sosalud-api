<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de procedimientos
 */

class AP extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaProcedimiento = '';
    public int $numeroAutorizacion = 0;
    public string $codigoProcedimiento = '';
    public int $ambitoProcedimiento = 0;
    public int $finalidadProcedimiento = 0;
    public string $personalAtiende = '';
    public string $diagnostico = '';
    public string $diagnostico1 = '';
    public string $diagnosticoComplicacion = '';
    public bool $actoQuirurgico = false;
    public float $valorProcedimiento = 0;
    protected int $id;
    protected string $nombreTabla = '';
    protected string $logError = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {

        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaProcedimiento,' .
            'numeroAutorizacion,' .
            'codigoProcedimiento,' .
            'ambitoProcedimiento,' .
            'finalidadProcedimiento,' .
            'personalAtiende,' .
            'diagnostico,' .
            'diagnostico1,' .
            'diagnosticoComplicacion,' .
            'actoQuirurgico,' .
            'valorProcedimiento';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AP';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];

                if (!empty($datoGuardar))
                {
                    if (Fechas::esFecha($datoGuardar))
                    {
                        $datoGuardar = Fechas::cambiarFormatoFecha($datoGuardar);
                    }

                    $this->{"$atributos[$i]"} = $datoGuardar;
                }
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

        $this->nombreTabla = 'tmp_AP_' . $nombreTabla;
        $this->logError = $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS $this->nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaProcedimiento date NOT NULL DEFAULT '0000-01-01',
            numeroAutorizacion int NOT NULL,
            codigoProcedimiento varchar(8) NOT NULL DEFAULT '',
            ambitoProcedimiento tinyint(1) NOT NULL DEFAULT '0',
            finalidadProcedimiento tinyint(1) NOT NULL DEFAULT '1',
            personalAtiende char(1) NOT NULL,
            diagnostico varchar(4) NOT NULL DEFAULT '0',
            diagnostico1 varchar(4) NOT NULL,
            diagnosticoComplicacion varchar(4) NOT NULL,
            actoQuirurgico tinyint(1) NOT NULL,
            valorProcedimiento double(15,2) NOT NULL,
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
            return DB::table($this->nombreTabla)->insertOrIgnore($values);
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
            0,
            '',
            0,
            0,
            '',
            '',
            '',
            '',
            false,
            0,
        ));
    }

    public function auditar()
    {
    }
}
