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
        // $this->tipoRips = $tipoRips;
        // $this->contenidoRips = $contenidoRips;

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

        $tipoRips = $detectarTipoRIPS["$tipoRips"];

        $tipoRips->agregarDatos($contenidoRips);

        $this->Rips = $tipoRips;
    }

    public function getTipoRips()
    {
        return $this->Rips;
    }
}
