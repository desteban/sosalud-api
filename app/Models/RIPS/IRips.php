<?php

namespace App\Models\RIPS;

interface IRips
{
    public function subirDB(array $datos);

    public function obtenerDatos(): array;

    public function agregarDatos(array $datos);

    public function tipoRIPS(): string;
}
