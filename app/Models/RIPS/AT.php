<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * Archivo de otros servicios
 */

class AT extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
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

        $cantidadAtributos = 11;
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
        return 'AT';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
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
    }

    public function subirDB()
    {

        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AT ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }

    public function crearTablas(string $nombreTabla)
    {
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
}
