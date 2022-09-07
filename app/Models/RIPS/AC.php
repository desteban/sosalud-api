<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de consulta
 */

class AC extends RIPS implements IRips
{
    public string $numeoFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaConsulta = '';
    public int $numeoAutorizacion = 0;
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

    public function obtenerDatos(): string
    {
        $datos = '';
        $indice = 0;

        foreach ($this as $clave => $valor)
        {
            if ($indice < 17)
            {

                $type = gettype($this->{$clave});
                $datos .= $this->typeToString($type, $valor) . ',';
                $indice++;
            }
        }

        $datos = rtrim($datos, ',');
        return $datos;
    }

    public function agregarDatos(array $datos)
    {

        $cantidadAtributos = 17;
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
        return 'AC';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaConsulta,' .
            'numeroAutorizacion,' .
            'codigoConsulta,' .
            'finalidadConsulta,' .
            'codigoCausaExterna,' .
            'diagnosticoPrincipal, ' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'tipoDiagnosticoPrincipal,' .
            'valorConsulta,' .
            'copago,' .
            'valorNeto';
    }

    public function subirDB()
    {
        //codigo para subir rips a la db
        $columnas = $this->obtenerColumnasDB();
        $datos = $this->obtenerDatos();
        $explode = explode(',', $datos);

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AC ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $explode);
        }
    }

    public function crearTablas(string $nombreTabla)
    {
        $respuesta = DB::statement("CREATE TABLE IF NOT EXISTS tmp_AC_$nombreTabla (
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

        dd($respuesta);
    }
}
