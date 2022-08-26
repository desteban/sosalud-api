<?php

namespace App\Models;

class Respuestas
{
    public string $titulo;
    public int $codigoHttp;
    public string $mensaje;
    public $data;

    public function __construct(int $codigoHttp = 200, string $titulo = 'succes', string $mensaje = '', $data = [])
    {

        $this->cambiarRespuesta($codigoHttp, $titulo, $mensaje, $data);
    }

    public function cambiarRespuesta(int $codigoHttp, string $titulo, string $mensaje = '', $data = [])
    {

        $this->codigoHttp = $codigoHttp;
        $this->estado = $titulo;
        $this->mensaje = $mensaje;
        $this->data = $data;
    }
}
