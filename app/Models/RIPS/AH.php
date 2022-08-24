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
}
