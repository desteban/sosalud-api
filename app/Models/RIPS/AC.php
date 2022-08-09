<?php

namespace App\Models\RIPS;

/**
 * Archivo de consulta
 */

class AC implements RIPS
{

    public string $numeoFactura = '';
    public string $codigoIPS = '';
    public string $tipoIdentificacion = '';
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
    private int $id;

    public function subirDB()
    {
        //codigo para subir rips a la db
    }

    public function obtenerDatos(): array
    {
        $datos = [];

        foreach ($this as $clave => $valor)
        {
            array_push($datos, $valor);
        }

        return $datos;
    }

    public function agregarDatos(array $datos)
    {

        foreach ($this as $clave => $valor)
        {
            echo "$clave => $valor\n";
        }
    }
}
