<?php

namespace App\Models\RIPS;

class RIPS
{

    protected function parceItem(string $tipoDato, $dato)
    {
        $tiposDatos = array(
            'boolean' => strlen($dato) > 0 ? true : false,
            'integer' => intval($dato),
            'double' => floatval($dato),
            'string' => "$dato"
        );

        return $tiposDatos[$tipoDato];
    }

    protected function typeToString(string $tipoDato, $dato)
    {

        $tiposDatos = array(
            'boolean' => $dato ? '1' : '0',
            'integer' => intval($dato),
            'double' => floatval($dato),
            'string' => "'$dato'"
        );

        return $tiposDatos[$tipoDato];
    }
}
