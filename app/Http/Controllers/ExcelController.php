<?php

namespace App\Http\Controllers;

use App\Exports\log;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController
{

    public function generarExcel()
    {
        return Excel::download(new log, 'listado.xlsx');
    }
}
