<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
//FromCollection, WithCustomStartCell,
class log implements WithTitle, FromCollection
{

    function __construct(protected string $nombreTabla = '')
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return DB::table('refips')->get(['*']);
    // }

    public function startCell(): string
    {
        return 'B2';
    }

    public function sheets(): array
    {
        $hojas = [];

        for ($i = 0; $i < 2; $i++)
        {
            $hojas[] = new RipsExport($i);
        }

        return $hojas;
    }

    public function title(): string
    {
        return 'Error contenido';
    }

    public function collection()
    {
        $datos = array_map(function ($texto)
        {
            return [$texto];
        }, $this->log);

        return collect($datos);
    }
}
