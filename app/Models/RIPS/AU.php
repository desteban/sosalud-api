<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de urgencia con observación
 */

class AU extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaIngreso = '';
    public string $horaIngreso = '';
    public string $numeroAutorizacion = '';
    public string $causaExterna = '';
    public string $giagnostico = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public int $referencia = 0;
    public string $estadoSalida = '';
    public string $CausaMuerte = '';
    public string $fechaSalida = '';
    public string $HoraSalida = '';
    protected int $id;

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaIngreso,' .
            'horaIngreso,' .
            'numeroAutorizacion,' .
            'causaExterna,' .
            'giagnostico,' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'referencia,' .
            'estadoSalida,' .
            'causaMuerte,' .
            'fechaSalida,' .
            'horaSalida';
    }

    public function tipoRIPS(): string
    {
        return 'AU';
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

        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AU_$nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaIngreso date NOT NULL DEFAULT '0000-01-01',
            horaIngreso time NOT NULL DEFAULT '00:00:00',
            numeroAutorizacion varchar(15) NOT NULL DEFAULT '',
            causaExterna char(2) NOT NULL DEFAULT '',
            diagnostico varchar(4) NOT NULL DEFAULT '',
            diagnostico1 varchar(4) DEFAULT NULL,
            diagnostico2 varchar(4) DEFAULT NULL,
            diagnostico3 varchar(4) DEFAULT NULL,
            referencia tinyint(1) NOT NULL DEFAULT '0',
            estadoSalida enum('1','2') NOT NULL DEFAULT '1',
            causaMuerte varchar(4) DEFAULT NULL,
            fechaSalida date DEFAULT NULL,
            horaSalida time DEFAULT NULL,
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
            DB::insert("INSERT INTO tmp_AU ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }
}
