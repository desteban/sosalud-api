<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class RipsExport implements FromCollection, WithTitle
{

    public function __construct(protected int $hoja)
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('refips')->get(['*']);
    }

    public function title(): string
    {
        return 'Hoja-' . $this->hoja;
    }
}
