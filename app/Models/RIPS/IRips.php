<?php

namespace App\Models\RIPS;

interface IRips
{
    public function subirDB(array $datos, string $tipoRips);

    public function obtenerDatos(): array;

    public function agregarDatos(array $datos);

    public function tipoRIPS(): string;

    public static function obtenerColumnasDB(): string;
}
