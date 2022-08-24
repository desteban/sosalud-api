<?php

namespace App\Models;

use App\Models\RIPS\AC;
use App\Models\RIPS\AF;
use App\Models\RIPS\AH;
use App\Models\RIPS\AM;
use App\Models\RIPS\AN;
use App\Models\RIPS\AP;
use App\Models\RIPS\AT;
use App\Models\RIPS\AU;
use App\Models\RIPS\CT;
use App\Models\RIPS\US;

class TipoRIPS
{
    private $Rips;

    function __construct($tipoRips = "", array $contenidoRips = [])
    {
        $contenido = $contenidoRips;
        $detectarTipoRIPS = [
            'AC' => new AC(),
            'AF' => new AF(),
            'AH' => new AH(),
            'AM' => new AM(),
            'AN' => new AN(),
            'AP' => new AP(),
            'AT' => new AT(),
            'AU' => new AU(),
            'CT' => new CT(),
            'US' => new US()
        ];

        //buscar si el RIP tiene fechas en su interior
        $indicesFechas = array_filter($contenidoRips, function ($registro)
        {

            return $this->esFecha($registro);
        });

        //verificar que el RIPS cuente con fechas
        if (count($indicesFechas))
        {

            foreach ($indicesFechas as $key => $value)
            {
                $contenido[$key] = $this->cambiarFormatoFecha($value);
            }
        }

        //escojer el tipo de RIPS adecuado
        $tipoRips = $detectarTipoRIPS["$tipoRips"];

        $tipoRips->agregarDatos($contenido);

        $this->Rips = $tipoRips;
    }

    public function getTipoRips()
    {
        return $this->Rips;
    }

    function cambiarFormatoFecha(string $fecha = null)
    {
        $buscarFecha = strpos($fecha, '/');
        if ($fecha && $buscarFecha)
        {
            return date_format(date_create_from_format('d/m/Y', $fecha), 'Y-m-d');
        }

        return null;
    }

    public function esFecha(string $fecha = null)
    {
        $fechaRegex = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/';
        return preg_match($fechaRegex, $fecha);
    }

    public function RipsToString(): string
    {
        return $this->Rips->obtenerDatos();
    }
}
