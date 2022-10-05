<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de consulta
 */

class AC extends RIPS implements IRips
{
    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaConsulta = '';
    public int $numeroAutorizacion = 0;
    public string $codigoConsulta = '';
    public string $finalidadConsulta = '';
    public string $codigoCausaExterna = '';
    public string $diagnosticoPrincipal = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public string $tipoDiagnosticoPrincipal = '';
    public string $valorConsulta = '';
    public string $copago = '';
    public string $valorNeto = '';
    protected int $id = 0;
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaConsulta,' .
            'numeroAutorizacion,' .
            'codigoConsulta,' .
            'finalidadConsulta,' .
            'codigoCausaExterna,' .
            'diagnosticoPrincipal,' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'tipoDiagnosticoPrincipal,' .
            'valorConsulta,' .
            'copago,' .
            'valorNeto';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AC';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        try
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
        catch (\Throwable $th)
        {
            return 0;
        }
    }

    public function obtenerDatos(bool $string = false): array | string
    {
        $atributos = $this->obtenerColumnasDB(true);
        $salidaArray = array();
        $salidaString = '';
        $salidaString = '';

        foreach ($atributos as $clave)
        {
            if ($string)
            {
                $salidaString .= $this->{$clave} . ',';
            }

            if (!$string)
            {
                if ($string)
                {
                    $salidaString .= $this->{$clave} . ',';
                }

                if (!$string)
                {
                    $salidaArray["$clave"] = $this->{$clave};
                }
            }

            if ($string)
            {
                return substr($salidaString, 0, -1);
            }
        }

        if ($string)
        {
            return substr($salidaString, 0, -1);
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {

        $this->nombreTabla = 'tmp_AC_' . $nombreTabla;
        return DB::statement("CREATE TABLE IF NOT EXISTS $this->nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaConsulta date NOT NULL DEFAULT '0000-01-01',
            numeroAutorizacion int NOT NULL,
            codigoConsulta varchar(8) NOT NULL DEFAULT '',
            finalidadConsulta char(2) NOT NULL DEFAULT '',
            codigoCausaExterna char(2) NOT NULL DEFAULT '',
            diagnosticoPrincipal varchar(4) NOT NULL DEFAULT '',
            diagnostico1 varchar(4) NOT NULL,
            diagnostico2 varchar(4) NOT NULL,
            diagnostico3 varchar(4) NOT NULL,
            tipoDiagnosticoPrincipal char(1) NOT NULL DEFAULT '',
            valorConsulta varchar(12) NOT NULL DEFAULT '',
            copago varchar(12) NOT NULL DEFAULT '',
            valorNeto varchar(12) NOT NULL DEFAULT '',
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr, numeroFactura)
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
            array_push($values, $this->obtenerDatos());
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
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ));
    }
}
