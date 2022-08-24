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

        $cantidadAtributos = 17;
        if (sizeof($datos) == $cantidadAtributos)
        {

            $indice = 0;

            foreach ($this as $clave => $valor)
            {
                if ($indice < $cantidadAtributos)
                {
                    $this->{$clave} = $datos[$indice];

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
            'iddentificacion,' .
            'fechaConsulta,' .
            'numeroAutorizacion,' .
            'codigoConsulta,' .
            'finalidadConsulta,' .
            'codigoCausaExterna,' .
            'diagnosticoPrincipal' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'tipoDiagnosticoPrincipal,' .
            'valorConsulta,' .
            'copago,' .
            'valorNeto';
    }
}
