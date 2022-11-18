<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RipsExport implements WithMultipleSheets, ShouldAutoSize
{

    public function __construct(protected string $codRips)
    {
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $hojas = array();
        $tipoRipsError = DB::select("
        SELECT tipo
        FROM tmp_logs_error_$this->codRips
        GROUP BY tipo;
        ");

        foreach ($tipoRipsError as $item)
        {
            $error = new ErrorExport($this->codRips, $item->tipo);
            array_push($hojas, $error);
        }

        return $hojas;
    }
}
