<?php

namespace App\Models;

class Respuestas
{
    public string $estado;
    public int $codigoHttp;
    public string $mensaje;
    public $data;

    public function __construct()
    {

        $this->estado = 'succes';
        $this->codigoHttp = 200;
        $this->mensaje = 'Todo ha salido bien';
    }

    public function cambiarRespuesta(int $codigoHttp, string $estado, string $mensaje = '', $data = [])
    {

        $this->estado = $estado;
        $this->codigoHttp = $codigoHttp;
        $this->mensaje = $mensaje;
        $this->data = $data;
    }
}
