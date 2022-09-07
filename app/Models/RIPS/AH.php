<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de hospitalización
 */

class AH extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $codigoViaIngreso = '';
    public string $fechaIngreso = '';
    public string $horaIngreso = '';
    public int $numeroAutorizacion = 0;
    public string $codigoCausaExterna = '';
    public string $diagnoticoIngreso = '';
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

        $cantidadAtributos = 19;
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
        return 'AH';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
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
    }

    public function subirDB()
    {

        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AH ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }

    public function crearTablas(string $nombreTabla)
    {
        $respuesta = DB::statement("CREATE TABLE IF NOT EXISTS tmp_AH_$nombreTabla (
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

        dd($respuesta);
    }
}
