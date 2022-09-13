<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de otros servicios
 */

class AT extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public int $numeroAutorizacion = 0;
    public int $tipoServicio = 0;
    public string $codigoServicio = '';
    public string $nombreServicio = '';
    public string $cantidad = '';
    public float $valorUnitario = 0;
    public float $valorTotal = 0;
    protected int $id;
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroAutorizacion,' .
            'tipoServicio,' .
            'codigoServicio,' .
            'nombreServicio,' .
            'cantidad,' .
            'valorUnitario,' .
            'valorTotal';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AT';
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

        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AT_$nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            numeroAutorizacion int NOT NULL,
            tipoServicio tinyint(1) NOT NULL DEFAULT '0',
            codigoServicio varchar(20) NOT NULL DEFAULT '',
            nombreServicio varchar(60) NOT NULL DEFAULT '',
            cantidad varchar(5) NOT NULL DEFAULT '',
            valorUnitario double(15,2) NOT NULL,
            valorTotal double(15,2) NOT NULL,
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );
          ");
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
            return DB::table("tmp_AT_$this->nombreTabla")->insert($values);
        }
        catch (\Throwable $th)
        {
            return false;
        }
    }
}
