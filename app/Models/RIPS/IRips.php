<?php

namespace App\Models\RIPS;

interface IRips
{
    public function subirDB();

    public function obtenerDatos(): string;

    public function agregarDatos(array $datos);

    public function tipoRIPS(): string;

    public static function obtenerColumnasDB(): string;
}
