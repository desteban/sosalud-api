<?php

namespace App\RIPS;

/**
 * Archivo de hospitalización
 */

class AH
{

    public string $numeroFactura;
    public string $codigoIPS;
    public string $tipoIdentificacion;
    public string $identificacion;
    public string $codigoViaIngreso;
    public string $fechaIngreso;
    public string $horaIngreso;
    public int $numeroAutorizacion;
    public string $codigoCausaExterna;
    public string $diagnoticoIngreso;
    public string $diagnosticoEgreso;
    public string $diagnostico1;
    public string $diagnostico2;
    public string $diagnostico3;
    public string $codigoCompicacion;
    public string $estadoSalida;
    public string $causaMuerte;
    public string $fechaEgreso;
    public string $horaEgreso;
    private int $id;
}
