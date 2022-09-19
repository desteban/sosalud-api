<?php

namespace App\Models\RIPS;

use Illuminate\Support\Facades\DB;

/**
 * 
 * Archivo de medicamentos
 */

class AM extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public int $numeroAutorizacion = 0;
    public string $codigoMedicamento = '';
    public string $tipoMedicamento = '0';
    public string $nombreGenerico = '';
    public string $formaFarmaceutica = '';
    public string $concentracionMedicamento = '';
    public string $unidadMedida = '';
    public string $numeroUnidad = '';
    public string $valorUnitarios = '';
    public string $valorTotal = '';
    protected int $id;
    protected string $nombreTabla = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroAutorizacion,' .
            'codigoMedicamento,' .
            'tipoMedicamento,' .
            'nombreGenerico,' .
            'formaFarmaceutica,' .
            'concentracionMedicamento,' .
            'unidadMedida,' .
            'numeroUnidad,' .
            'valorUnitario,' .
            'valorTotal';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AM';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];

                $this->{"$atributos[$i]"} = $datoGuardar;
            }
        }
    }

    public function obtenerDatos(): array
    {
        $atributos = $this->obtenerColumnasDB(true);
        $salidaArray = array();

        foreach ($atributos as $clave)
        {
            $salidaArray["$clave"] = $this->{$clave};
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {

        $this->nombreTabla = $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AM_$nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            numeroAutorizacion int NOT NULL,
            codigoMedicamento varchar(20) NOT NULL DEFAULT '',
            tipoMedicamento enum('1','2') DEFAULT NULL,
            nombreGenerico varchar(30) NOT NULL DEFAULT '',
            formaFarmaceutica varchar(20) NOT NULL DEFAULT '',
            concentracionMedicamento varchar(20) NOT NULL DEFAULT '',
            unidadMedida varchar(20) NOT NULL DEFAULT '',
            numeroUnidad varchar(5) NOT NULL DEFAULT '',
            valorUnitario varchar(15) NOT NULL,
            valorTotal varchar(15) NOT NULL,
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");
    }

    public function subirDB(array $datos = []): bool
    {

        $values = array();

        foreach ($datos as $linea)
        {

            $lineaLimpia = str_replace(array("\r\n", "\r", "\n", " "), "", $linea);
            $datosArray = explode(',', $lineaLimpia);

            $this->datosDefecto();
            $this->agregarDatos($datosArray);
            array_push($values, $this->obtenerDatos(true));
        }

        try
        {
            return DB::table("tmp_AM_$this->nombreTabla")->insert($values);
        }
        catch (\Throwable $th)
        {
            return false;
        }
    }

    protected function datosDefecto(): void
    {
        $this->agregarDatos(array(
            '',
            '',
            '',
            '',
            0,
            '',
            '0',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ));
    }
}
