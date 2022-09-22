<?php

namespace App\DataBase;

class DataBase
{
    public static function validarNumeroFactura(string $tablaValidar, string $tablaValidadora): string
    {
        return "SELECT * FROM $tablaValidar
        LEFT JOIN $tablaValidadora 
        ON $tablaValidadora.codigoIps = $tablaValidar.codigoIps 
        AND $tablaValidadora.numeroFactura = $tablaValidar.numeroFactura
        WHERE $tablaValidadora.codigoIps is NULL;";
    }

    public static function validarIdentificacion(string $tablaValidar, string $tablaValidadora): string
    {
        return "SELECT * FROM $tablaValidar
        LEFT JOIN $tablaValidadora 
        ON $tablaValidadora.tipoIdentificacion = $tablaValidar.tipoIdentificacion 
        AND $tablaValidadora.identificacion = $tablaValidar.identificacion
        WHERE $tablaValidadora.identificacion is NULL;";
    }
}
