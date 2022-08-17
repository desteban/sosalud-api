<?php

namespace App\Models\RIPS;

class RIPS
{
    public function subirDB(array $datos)
    {
        //codigo para subir rips a la db
        echo 'Subiendo a db...' . $datos[0]->tipoRIPS() . "\n";
    }

    private function seleccionarTablaDB(string $tipoRIPS): string
    {
        $tablas = array(
            'AC' => 'tmp_AC',
            'AF' => 'tmp_AF',
            'AH' => 'tmp_AH',
            'AM' => 'tmp_AM',
            'AN' => 'tmp_AN',
            'AP' => 'tmp_AP',
            'AT' => 'tmp_AT',
            'AU' => 'tmp_AU',
            'CT' => 'tmp_CT',
            'US' => 'tmp_US',
        );

        return $tablas[$tipoRIPS];
    }
}
