<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 *  Archivo de transacciones
 */

class AF extends RIPS implements IRips
{
    /**
     * codigoEntidadAdministradora = codigoEapb
     * nombreEntidadAdministradora = nombreEapb
     */
    public string $codigoIps = '';
    public string $nombreIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $numeroFactura = '';
    public string $fechaFactura = '';
    public string $fechaInicio = '';
    public string $fechaFinal = '';
    public string $codigoEapb = '';
    public string $nombreEapb = '';
    public string $numeroContrato = '';
    public string $planBeneficios = '';
    public string $numeroPoliza = '';
    public float $copago = 0;
    public float $valorComision = 0;
    public float $valorDescuentos = 0;
    public float $valorFactura = 0;
    protected int $id;


    public static function obtenerColumnasDB(): string
    {
        return 'codigoIps,' .
            'nombreIps, ' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'numeroFactura,' .
            'fechaFactura,' .
            'fechaInicio,' .
            'fechaFinal,' .
            'codigoEapb,' .
            'nombreEapb,' .
            'numeroContrato,' .
            'planBeneficios,' .
            'numeroPoliza,' .
            'copago,' .
            'valorComision,' .
            'valorDescuentos,' .
            'valorFactura';
    }

    public function tipoRIPS(): string
    {
        return 'AF';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = explode(',', $this->obtenerColumnasDB());

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];

                if (Fechas::esFecha($datoGuardar))
                {
                    $datoGuardar = Fechas::cambiarFormatoFecha($datoGuardar);
                }

                $this->{"$atributos[$i]"} = $datoGuardar;
            }
        }
    }

    public function obtenerDatos(): string
    {
        $datos = '';

        foreach ($this as $clave => $valor)
        {
            $type = gettype($this->{$clave});
            $datos .= $this->typeToString($type, $valor) . ',';
        }

        $datos = rtrim($datos, ',');
        return $datos;
    }

    public function crearTablas(string $nombreTabla)
    {
        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AF_$nombreTabla (
            codigoIps varchar(20) NOT NULL DEFAULT '',
            nombreIps varchar(60) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            fechaFactura date NOT NULL DEFAULT '0000-01-01',
            fechaInicio date NOT NULL DEFAULT '0000-01-01',
            fechaFinal date NOT NULL DEFAULT '0000-01-01',
            codigoEapb varchar(6) NOT NULL DEFAULT '',
            nombreEapb varchar(30) NOT NULL DEFAULT '',
            numeroContrato varchar(15) NOT NULL DEFAULT '',
            planBeneficios varchar(30) NOT NULL DEFAULT '',
            numeroPoliza varchar(10) NOT NULL DEFAULT '',
            copago double(15,2) NOT NULL DEFAULT '0.00',
            valorComision double(15,2) NOT NULL DEFAULT '0.00',
            valorDescuentos double(15,2) NOT NULL DEFAULT '0.00',
            valorFactura double(15,2) NOT NULL DEFAULT '0.00',
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr)
          );");
    }

    public function subirDB(array $datos = [])
    {
        //codigo para subir rips a la db
        $columnas = $this->obtenerColumnasDB();
        $explode = explode(',', $this->obtenerDatos());

        if ($columnas)
        {
            DB::insert("INSERT INTO tmp_AF ($columnas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", $explode);
        }
    }
}
