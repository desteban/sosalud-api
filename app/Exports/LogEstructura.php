<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;

class LogEstructura implements FromCollection, WithCustomStartCell, WithTitle, ShouldAutoSize
{

    use Exportable;

    function __construct(protected array $log = ['Hola', 'Error'])
    {
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function title(): string
    {
        return 'Errores';
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
