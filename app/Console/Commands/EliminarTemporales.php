<?php

namespace App\Console\Commands;

use App\Util\ArchivosUtil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EliminarTemporales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Temporales:Eliminar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar archivos temporales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //eliminar tablas temporales
        DB::statement('CALL limpiarTablasTemporales();');

        //elimiar archivos temporales en el servidor
        ArchivosUtil::eliminarArchivosTemporales();
    }
}
