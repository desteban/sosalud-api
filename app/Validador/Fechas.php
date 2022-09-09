<?php

namespace App\Validador;

class Fechas
{
    public static function cambiarFormatoFecha(string $fecha)
    {
        $buscarFecha = strpos($fecha, '/');
        if ($fecha && $buscarFecha)
        {
            return date_format(date_create_from_format('d/m/Y', $fecha), 'Y-m-d');
        }

        return null;
    }

    public static function esFecha(string $fecha)
    {
        $fechaRegex = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/';
        return preg_match($fechaRegex, $fecha);
    }
}
