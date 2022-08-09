<?php

namespace App\Models;


class TipoRIPS
{
    private string $tipoRips;
    public array $contenidoRips;

    function __construct(string $tipoRips = "", array $contenidoRips = [])
    {
        $this->tipoRips = $tipoRips;
        $this->contenidoRips = $contenidoRips;
    }

    public function modificarContenido(callable $callback)
    {
        array_map($callback, $this->contenidoRips);
    }

    public function getTipoRips()
    {
        return $this->tipoRips;
    }
}
