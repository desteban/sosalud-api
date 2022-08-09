<?php

namespace App\Models;

class Respuestas
{
    public string $estado;
    public int $codigoHttp;
    public string $mensaje;
    public array $data;

    public function __construct()
    {

        $this->estado = 'succes';
        $this->codigoHttp = 200;
        $this->mensaje = 'Todo ha salido bien';
        $this->data = [];
    }

    public function cambiarRespuesta(string $estado, int $codigoHttp, string $mensaje, array $data)
    {

        $this->estado = $estado;
        $this->codigoHttp = $codigoHttp;
        $this->mensaje = $mensaje;
        $this->data = $data;
    }
}
