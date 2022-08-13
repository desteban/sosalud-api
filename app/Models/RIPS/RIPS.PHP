<?php

namespace App\Models\RIPS;

interface RIPS
{

    public function subirDB();

    public function obtenerDatos(): array;

    public function agregarDatos(array $datos);
}
