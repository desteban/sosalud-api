<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

class RIPS
{

    public function subirDB(array $datos, string $tipoRIPS)
    {
        //codigo para subir rips a la db
        $columnas = $this->columnasTablas($tipoRIPS) . '';
        $tabla = "tmp_$tipoRIPS";

        if ($columnas)
        {
            dd($datos);

            DB::insert("INSERT INTO $tabla ($columnas) VALUES (?)", $datos);
        }
    }

    private function columnasTablas(string $tipoRIPS): string
    {
        $tablas = array(
            'AC' => AC::obtenerColumnasDB(),
            'AF' => AF::obtenerColumnasDB(),
            'AH' => AH::obtenerColumnasDB(),
            'AM' => AM::obtenerColumnasDB(),
            'AN' => AN::obtenerColumnasDB(),
            'AP' => AP::obtenerColumnasDB(),
            'AT' => AT::obtenerColumnasDB(),
            'AU' => AU::obtenerColumnasDB(),
            'CT' => CT::obtenerColumnasDB(),
            'US' => US::obtenerColumnasDB(),
        );

        $encontrado = array_key_exists($tipoRIPS, $tablas);

        if ($encontrado)
        {
            return $tablas[$tipoRIPS];
        }

        return null;
    }
}
