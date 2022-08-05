<?php

namespace App\RIPS;

/**
 * Archivo de urgencia con observación
 */

class AU
{

    public string $numeroFactura;
    public string $codigoIPS;
    public string $tipoIdentificacion;
    public string $identificacion;
    public string $fechaIngreso;
    public string $horaIngreso;
    public string $numeroAutorizacion;
    public string $causaExterna;
    public string $diagnostico;
    public string $diagnostico1;
    public string $diagnostico2;
    public string $diagnostico3;
    public int $referencia;
    public string $estadoSalida;
    public string $CausaMuerte;
    public string $fechaSalida;
    public string $HoraSalida;
    private int $id;
}
