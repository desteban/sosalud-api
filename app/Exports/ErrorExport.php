<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ErrorExport implements FromCollection, ShouldAutoSize, WithTitle
{

    public function __construct(
        protected string $tabla,
        protected string $tipo
    )
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('tmp_logs_error_' . $this->tabla)
            ->where('tipo', '=', $this->tipo)
            ->get(['*']);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Error - ' . $this->tipo;
    }
}
