<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class log implements FromCollection, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('refips')->get(['*']);
    }

    public function startCell(): string
    {
        return 'B2';
    }
}
