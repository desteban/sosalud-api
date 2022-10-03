<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class LogEstructura implements FromCollection
{

    use Exportable;

    function __construct(protected array $log = ['Hola', 'Error'])
    {
    }

    public function startCell(): string
    {
        return 'B2';
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
