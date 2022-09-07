<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * 
 * Archivo de medicamentos
 */

class AM extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public int $numeroAutorizacion = 0;
    public string $codigoMedicamento = '';
    public string $tipoMedicamento = '0';
    public string $nombreGenerico = '';
    public string $formaFarmaceutica = '';
    public string $concentracionMedicamento = '';
    public string $unidadMedida = '';
    public string $numeroUnidad = '';
    public string $valorUnitarios = '';
    public string $valorTotal = '';
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

        $cantidadAtributos = 14;
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
        return 'AM';
    }

    public static function obtenerColumnasDB(): string
    {
        return 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroAutorizacion,' .
            'codigoMedicamento,' .
            'tipoMedicamento,' .
            'nombreGenerico,' .
            'formaFarmaceutica,' .
            'concentracionMedicamento,' .
            'unidadMedida,' .
            'numeroUnidad,' .
            'valorUnitario,' .
            'valorTotal';
    }

    public function subirDB()
    {

        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AM ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }

    public function crearTablas(string $nombreTabla)
    {
        $respuesta = DB::statement("CREATE TABLE IF NOT EXISTS tmp_AM_$nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            numeroAutorizacion int NOT NULL,
            codigoMedicamento varchar(20) NOT NULL DEFAULT '',
            tipoMedicamento enum('1','2') DEFAULT NULL,
            nombreGenerico varchar(30) NOT NULL DEFAULT '',
            formaFarmaceutica varchar(20) NOT NULL DEFAULT '',
            concentracionMedicamento varchar(20) NOT NULL DEFAULT '',
            unidadMedida varchar(20) NOT NULL DEFAULT '',
            numeroUnidad varchar(5) NOT NULL DEFAULT '',
            valorUnitario varchar(15) NOT NULL,
            valorTotal varchar(15) NOT NULL,
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");

        dd($respuesta);
    }
}
