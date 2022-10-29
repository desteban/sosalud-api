<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de hospitalización
 */

class AH extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoViaIngreso = '';
    public string $fechaIngreso = '';
    public string $horaIngreso = '';
    public int $numeroAutorizacion = 0;
    public string $codigoCausaExterna = '';
    public string $diagnosticoIngreso = '';
    public string $diagnosticoEgreso = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public string $codigoComplicacion = '';
    public string $estadoSalida = '';
    public string $causaMuerte = '';
    public string $fechaEgreso = '';
    public string $horaEgreso = '';
    protected int $id;
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'codigoViaIngreso,' .
            'fechaIngreso,' .
            'horaIngreso,' .
            'numeroAutorizacion,' .
            'codigoCausaExterna,' .
            'diagnosticoIngreso,' .
            'diagnosticoEgreso,' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'codigoComplicacion,' .
            'estadoSalida,' .
            'causaMuerte,' .
            'fechaEgreso,' .
            'horaEgreso';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AH';
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

        $this->nombreTabla = 'tmp_AH_' . $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS $this->nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(24) NOT NULL DEFAULT '',
            codigoViaIngreso char(1) NOT NULL DEFAULT '',
            fechaIngreso date NOT NULL DEFAULT '0000-01-01',
            horaIngreso time NOT NULL DEFAULT '00:00:00',
            numeroAutorizacion int DEFAULT NULL,
            codigoCausaExterna char(2) NOT NULL DEFAULT '',
            diagnosticoIngreso varchar(4) NOT NULL DEFAULT '',
            diagnosticoEgreso varchar(4) DEFAULT NULL,
            diagnostico1 varchar(4) DEFAULT NULL,
            diagnostico2 varchar(4) DEFAULT NULL,
            diagnostico3 varchar(4) DEFAULT NULL,
            codigoComplicacion varchar(4) DEFAULT NULL,
            estadoSalida enum('1','2') NOT NULL DEFAULT '1',
            causaMuerte varchar(4) DEFAULT NULL,
            fechaEgreso date NOT NULL DEFAULT '0000-01-01',
            horaEgreso time NOT NULL DEFAULT '00:00:00',
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

    public function auditar()
    {
    }
}
