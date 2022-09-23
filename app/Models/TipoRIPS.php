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
use App\Models\RIPS\IRips;
use App\Models\RIPS\US;

class TipoRIPS
{

    public static function listadoRips(bool $conRips = true): array
    {
        if ($conRips)
        {

            return [
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
        }

        return [
            'AC',
            'AF',
            'AH',
            'AM',
            'AN',
            'AP',
            'AT',
            'AU',
            'CT',
            'US'
        ];
    }

    public static function escojerRips(string $tipoRips): IRips | null
    {
        $listadoRips = TipoRIPS::listadoRips();

        if (array_key_exists($tipoRips, $listadoRips))
        {
            return $listadoRips[$tipoRips];
        }

        return null;
    }
}
