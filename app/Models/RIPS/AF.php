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
    protected string $nombreTabla = '';
    protected string $logsError = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'codigoIps,' .
            'nombreIps,' .
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

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AF';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        if (sizeof($atributos) == sizeof($datos))
        {
            for ($i = 0; $i < sizeof($atributos); $i++)
            {
                $datoGuardar = $datos[$i];
                if (!empty($datoGuardar))
                {
                    if (Fechas::esFecha($datoGuardar))
                    {
                        $datoGuardar = Fechas::cambiarFormatoFecha($datoGuardar);
                    }

                    $this->{"$atributos[$i]"} = $datoGuardar;
                }
            }
        }
    }

    public function obtenerDatos(): array
    {
        $atributos = $this->obtenerColumnasDB(true);
        $salidaArray = array();

        foreach ($atributos as $clave)
        {
            $dato = $this->{"$clave"};
            $salidaArray["$clave"] = $dato;
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {
        $this->nombreTabla = 'tmp_AF_' . $nombreTabla;
        $this->logsError = $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS $this->nombreTabla (
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
            return DB::table($this->nombreTabla)->insertOrIgnore($values);
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
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            0,
            0,
            0,
            0,
        ));
    }

    public function auditar()
    {
        $tablaError = 'tmp_logs_error_' . $this->logsError;

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
            CONCAT(
                'Error en la linea: ',
                 nr,
                  ' del archivo AF, el codigo ',
                codigoIps,
                ' no se relaciona con la identificación (',
                tipoIdentificacion, ') ',
                identificacion
                ),
            'AF'
            FROM
            (
            SELECT
                tmp_AF_$this->logsError.codigoIps,
                tmp_AF_$this->logsError.nr,
                tmp_AF_$this->logsError.tipoIdentificacion,
                tmp_AF_$this->logsError.identificacion
            FROM tmp_AF_$this->logsError
                LEFT JOIN refIps ON refIps.codigo=tmp_AF_$this->logsError.codigoIps and
                refIps.tipoIdentificacion=tmp_AF_$this->logsError.tipoIdentificacion and
                refIps.identificacion=tmp_AF_$this->logsError.identificacion
            WHERE refIps.tipoIdentificacion is null
            ) 
            as error;");

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', codigoIps, ' no pertenece a una IPS registrada,
                error en la linea: ', nr, ' del archivo AF'),
                'AF'
            FROM
            (
            SELECT
                tmp_AF_$this->logsError.codigoIps,
                tmp_AF_$this->logsError.nr
            FROM tmp_AF_$this->logsError
                LEFT JOIN refIps ON tmp_AF_$this->logsError.codigoIps=refIps.codigo
                WHERE refIps.codigo is NULL
            ) as error;");
    }
}
