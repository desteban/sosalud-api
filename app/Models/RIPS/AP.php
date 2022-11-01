<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de procedimientos
 */

class AP extends RIPS implements IRips
{

    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaProcedimiento = '';
    public int $numeroAutorizacion = 0;
    public string $codigoProcedimiento = '';
    public int $ambitoProcedimiento = 0;
    public int $finalidadProcedimiento = 0;
    public string $personalAtiende = '';
    public string $diagnostico = '';
    public string $diagnostico1 = '';
    public string $diagnosticoComplicacion = '';
    public bool $actoQuirurgico = false;
    public float $valorProcedimiento = 0;
    protected int $id;
    protected string $nombreTabla = '';
    protected string $logError = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {

        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaProcedimiento,' .
            'numeroAutorizacion,' .
            'codigoProcedimiento,' .
            'ambitoProcedimiento,' .
            'finalidadProcedimiento,' .
            'personalAtiende,' .
            'diagnostico,' .
            'diagnostico1,' .
            'diagnosticoComplicacion,' .
            'actoQuirurgico,' .
            'valorProcedimiento';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AP';
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
            $salidaArray["$clave"] = $this->{$clave};
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {

        $this->nombreTabla = 'tmp_AP_' . $nombreTabla;
        $this->logError = $nombreTabla;

        return DB::statement("CREATE TABLE IF NOT EXISTS $this->nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaProcedimiento date NOT NULL DEFAULT '0000-01-01',
            numeroAutorizacion int NOT NULL,
            codigoProcedimiento varchar(8) NOT NULL DEFAULT '',
            ambitoProcedimiento tinyint(1) NOT NULL DEFAULT '0',
            finalidadProcedimiento tinyint(1) NOT NULL DEFAULT '1',
            personalAtiende char(1) NOT NULL,
            diagnostico varchar(4) NOT NULL DEFAULT '0',
            diagnostico1 varchar(4) NOT NULL,
            diagnosticoComplicacion varchar(4) NOT NULL,
            actoQuirurgico tinyint(1) NOT NULL,
            valorProcedimiento double(15,2) NOT NULL,
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
            0,
            '',
            0,
            0,
            '',
            '',
            '',
            '',
            false,
            0,
        ));
    }

    public function auditar()
    {
        $tablaError = 'tmp_logs_error_' . $this->logError;

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
            CONCAT('El codigo ', codigoProcedimiento, ' no corresponde a los CUPS registrados,
            error en la linea: ', nr, ' del archivo AP'),
            'AP'
            FROM
            (
            SELECT
                tmp_AP_$this->logError.codigoProcedimiento,
                tmp_AP_$this->logError.nr
                FROM tmp_AP_$this->logError
                LEFT JOIN refCups ON refCups.codigo = tmp_AP_$this->logError.codigoProcedimiento
                WHERE refCups.descrip is null
        ) as error;");

        //!fi & ff no estan en refCups
        // DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        // select 
        //     CONCAT('El codigo ', codigoProcedimiento, 
        // ' no corresponde a los CUPS registrados, error en la linea: ', nr, ' del archivo AP'),
        //     'AP'
        //     FROM
        //     (
        //     SELECT
        //         tmp_AP_$this->logError.codigoProcedimiento,
        //         tmp_AP_$this->logError.nr
        //         FROM tmp_AP_$this->logError
        //         LEFT JOIN refCups ON refCups.codigo = tmp_AP_$this->logError.codigoProcedimiento
        //         WHERE refCups.descrip is not null 
        //         and !(tmp_AP_$this->logError.fechaProcedimiento between refCups.fi and refCups.ff)
        //     ) as error;");

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
        CONCAT('El codigo ', codigoProcedimiento, 
        ' no corresponde a los CUPS registrados, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_AP_$this->logError.codigoProcedimiento,
            tmp_AP_$this->logError.nr
            FROM tmp_AP_$this->logError
        LEFT JOIN refCups ON refCups.codigo = tmp_AP_$this->logError.codigoProcedimiento 
        WHERE refCups.descrip is not null and refCups.AT != 'P'
        ) as error;");

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
            CONCAT('Error en la linea: ',
                 nr,
                  ' del archivo AP, el codigo ',
                codigoProcedimiento,
                ' no se relaciona con la identificación (',
                tipoIdentificacion, ') ',
                identificacion
                ),
            'AP'
            FROM
            (
            SELECT
                tmp_AP_$this->logError.codigoProcedimiento,
                tmp_AP_$this->logError.tipoIdentificacion,
                tmp_AP_$this->logError.identificacion,
                tmp_AP_$this->logError.nr
                FROM tmp_AP_$this->logError
            LEFT JOIN refCups ON refCups.codigo = tmp_AP_$this->logError.codigoProcedimiento
            LEFT JOIN tmp_US_$this->logError 
            on tmp_US_$this->logError.tipoIdentificacion=tmp_AP_$this->logError.tipoIdentificacion 
            and tmp_US_$this->logError.identificacion=tmp_AP_$this->logError.identificacion
            WHERE (refCups.genero!= 'A' and refCups.genero!=tmp_US_$this->logError.genero)
            ) as error;");

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
            CONCAT('El codigo ', finalidadProcedimiento, 
            ' no corresponde al procedimiento, error en la linea: ', nr, ' del archivo AP'),
            'AP'
            FROM
            (
            SELECT
                tmp_AP_$this->logError.finalidadProcedimiento,
                tmp_AP_$this->logError.nr
                FROM tmp_AP_$this->logError
            LEFT JOIN refFinalidadProcedimiento 
            ON tmp_AP_$this->logError.finalidadProcedimiento=refFinalidadProcedimiento.codigo
            WHERE refFinalidadProcedimiento.codigo is NULL
            ) as error;");

        DB::statement(query: "INSERT INTO $tablaError (contenido, tipo)
        select 
            CONCAT('El codigo ', personalAtiende, ' no coincide, error en la linea: ', nr, ' del archivo AP'),
            'AP'
            FROM
            (
            SELECT
                tmp_AP_$this->logError.personalAtiende,
                tmp_AP_$this->logError.nr
                FROM tmp_AP_$this->logError
            LEFT JOIN refPersonalAtiende ON tmp_AP_$this->logError.personalAtiende=refPersonalAtiende.codigo
            WHERE ('721001' <= tmp_AP_$this->logError.codigoProcedimiento 
            and tmp_AP_$this->logError.codigoProcedimiento <= '740300') 
            and refPersonalAtiende.codigo is NULL
            ) as error;");

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', diagnostico, 
                ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
                'AP'
                FROM
                (
                SELECT
                    tmp_AP_$this->logError.diagnostico,
                    tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$this->logError.diagnostico
                WHERE tmp_AP_$this->logError.codigoProcedimiento <= '870000' and refCie10.descrip is null
                ) as error;"
        );

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
            CONCAT('El codigo ', diagnostico, 
            ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
                'AP'
                FROM
                (
                SELECT
                    tmp_AP_$this->logError.diagnostico,
                    tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$this->logError.diagnostico
                WHERE tmp_AP_$this->logError.diagnostico != '' and refCie10.descrip is null
                ) as error;"
        );

        //!Que es $wEdad
        // DB::statement(
        //     query: "INSERT INTO $tablaError (contenido, tipo)
        //     select 
        //         CONCAT (tmp_ap.diagnostico,' - (',refCie10.eMin,') ',
        //         $wEdad,' (',refCie10.eMax,
        //         ') Error en diagnostico principal con referencia a la edad del maestro de afiliados'),
        //         'AP'
        //         FROM
        //         (
        //         SELECT
        //             tmp_ap.diagnostico,
        //             tmp_ap.tipoIdentificacion
        //             tmp_ap.identificacion
        //             tmp_AP.nr
        //             FROM tmp_AP
        //         LEFT JOIN refCie10 
        //         ON refCie10.codigo = tmp_ap.diagnostico
        //         LEFT JOIN maestroIdentificaciones 
        //         ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion 
        //         and maestroIdentificaciones.identificacion=tmp_ap.identificacion
        //         LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
        //         WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)',';
        //         ) as error;"
        // );

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('Error en la linea: ',
                     nr,
                      ' del archivo AP, el diagnostico ',
                    diagnostico,
                    ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ',
                    identificacion
                    ),
                'AP'
                FROM
                (
                SELECT
                    tmp_AP_$this->logError.diagnostico,
                    tmp_AP_$this->logError.tipoIdentificacion,
                    tmp_AP_$this->logError.identificacion,
                    tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AP_$this->logError.diagnostico
                LEFT JOIN tmp_US_$this->logError 
                on tmp_US_$this->logError.tipoIdentificacion=tmp_AP_$this->logError.tipoIdentificacion 
                and tmp_US_$this->logError.identificacion=tmp_AP_$this->logError.identificacion
                WHERE (refCie10.genero!= '' and refCie10.genero!=tmp_US_$this->logError.genero)
                ) as error;"
        );

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', diagnostico1, ' no pertenece al diagnostico,
                error en la linea: ', nr, ' del archivo AP'),
                'AP'
                FROM
                (
                SELECT
                    tmp_AP_$this->logError.diagnostico1,
                    tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                LEFT JOIN refCie10 ON tmp_AP_$this->logError.diagnostico1=refCie10.codigo
                WHERE refCie10.descrip is null and tmp_AP_$this->logError.diagnostico1!=''
                ) as error;"
        );

        //!que es wedad
        // DB::statement(
        //     query: "INSERT INTO $tablaError (contenido, tipo)
        //     select 
        //         CONCAT('Error en la linea: ', nr, ' del archivo AP, el diagnostico1 ',
        //             diagnostico, ' no se relaciona con la identificación (',
        //             tipoIdentificacion, ') ', identificacion ),
        //         'AP'
        //         FROM (
        //             SELECT
        //                 tmp_AP_$his->logError.diagnostico1,
        //                 tmp_AP_$his->logError.tipoIdentificacion
        //                 tmp_AP_$his->logError.identificacion
        //                 tmp_AP_$his->logError.nr
        //             FROM tmp_AP_$his->logError
        //                 LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$his->logError.diagnostico1
        //                 LEFT JOIN maestroIdentificaciones ON
        //                 maestroIdentificaciones.tipoIdentificacion=tmp_AP_$his->logError.tipoIdentificacion and 
        //                 maestroIdentificaciones.identificacion=tmp_AP_$his->logError.identificacion
        //                 LEFT JOIN maestroAfiliados ON 
        //                 maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
        //             WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and 
        //                  tmp_AP_$his->logError.diagnostico1!=''
        //             ) as error;"
        // );

        //!revisar diagnostico
        // DB::statement(
        //     query: "INSERT INTO $tablaError (contenido, tipo)
        //     select 
        //         CONCAT('Error en la linea: ',
        //              nr,
        //               ' del archivo AP, el diagnostico2 ',
        //             diagnostico,
        //             ' no se relaciona con la identificación (',
        //             tipoIdentificacion, ') ',
        //             identificacion
        //             ),
        //         'AP'
        //         FROM (
        //             SELECT
        //                 tmp_AP_$this->logError.diagnostico1,
        //                 tmp_AP_$this->logError.tipoIdentificacion,
        //                 tmp_AP_$this->logError.identificacion,
        //                 tmp_AP_$this->logError.nr,
        //             FROM tmp_AP_$this->logError
        //                 LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$this->logError.diagnostico1
        //                 LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_AP_$this->logError.tipoIdentificacion
        //                 and ripsUS.identificacion=tmp_AP_$this->logError.identificacion
        //             WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and 
        //                 tmp_AP_$this->logError.diagnostico1!=''
        //             ) as error;"
        // );

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', diagnosticoComplicacion, ' no pertenece al diagnostico, 
                error en la linea: ', nr, ' del archivo AP'),
                'AP'
                FROM (
                    SELECT
                        tmp_AP_$this->logError.diagnosticoComplicacion,
                        tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                        LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$this->logError.diagnosticoComplicacion 
                    WHERE tmp_AP_$this->logError.diagnosticoComplicacion!='' and refCie10.descrip is null
                ) as error;"
        );

        //!que es wEdad
        // DB::statement(
        //     query: "INSERT INTO $tablaError (contenido, tipo)
        //     select 
        //         CONCAT('Error en la linea: ',
        //              nr, ' del archivo AP, el diagnostico ',
        //             diagnosticoComplicacion,
        //             ' no se relaciona con la identificación (',
        //             tipoIdentificacion, ') ', identificacion
        //             ),
        //         'AP'
        //         FROM (
        //             SELECT
        //                 tmp_AP_$this->logError.diagnosticoComplicacion,
        //                 tmp_AP_$this->logError.tipoIdentificacion
        //                 tmp_AP_$this->logError.identificacion
        //                 tmp_AP_$this->logError.nr
        //             FROM tmp_AP_$this->logError
        //                 LEFT JOIN refCie10 ON refCie10.codigo = tmp_AP_$this->logError.diagnosticoComplicacion
        //                 LEFT JOIN maestroIdentificaciones ON 
        //                 maestroIdentificaciones.tipoIdentificacion=tmp_AP_$this->logError.tipoIdentificacion and 
        //                 maestroIdentificaciones.identificacion=tmp_AP_$this->logError.identificacion
        //                 LEFT JOIN maestroAfiliados 
        //                 ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
        //             WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)
        //             ) as error;"
        // );

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('Error en la linea: ',
                     nr,' del archivo AP, el diagnostico ',
                    diagnosticoComplicacion, ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                ),
                'AP'
                FROM (
                    SELECT
                        tmp_AP_$this->logError.diagnosticoComplicacion,
                        tmp_AP_$this->logError.tipoIdentificacion,
                        tmp_AP_$this->logError.identificacion,
                        tmp_AP_$this->logError.nr
                    FROM tmp_AP_$this->logError
                        LEFT JOIN refCie10 ON refCie10.codigo=tmp_AP_$this->logError.diagnosticoComplicacion
                        LEFT JOIN tmp_US_$this->logError ON 
                        tmp_US_$this->logError.tipoIdentificacion=tmp_AP_$this->logError.tipoIdentificacion AND
                        tmp_US_$this->logError.identificacion=tmp_AP_$this->logError.identificacion
                    WHERE (refCie10.genero!= '' and refCie10.genero!=tmp_US_$this->logError.genero)
                    ) 
                    as error;"
        );

        //!revisar el mensaje (linea 452)
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', codigoIps,
                ' no pertenece a una IPS registrada, error en la linea: ', nr, ' del archivo AF'),
                'AP'
                FROM (
                    SELECT
                        tmp_AP_$this->logError.actoQuirurgico,
                        tmp_AP_$this->logError.nr,
                        tmp_AP_$this->logError.codigoIps
                    FROM tmp_AP_$this->logError
                    LEFT JOIN refActoQuirurgico ON
                    refActoQuirurgico.codigo = tmp_AP_$this->logError.actoQuirurgico
                    WHERE tmp_AP_$this->logError.actoQuirurgico !='' AND
                    refActoQuirurgico.descrip IS NULL
                    ) as error;"
        );

        //! la tabla maestroRedTarifas no existe
        // DB::statement(
        //     query: "INSERT INTO $tablaError (contenido, tipo)
        //     select 
        //         CONCAT('Error en la linea: ',
        //              nr, ' del archivo AP, el codigo diagnostico ',
        //             codigoIps, codigoProcedimiento,
        //             ' no se relaciona con la identificación (',
        //             tipoIdentificacion, ') ', identificacion
        //             ),
        //         'AP'
        //         FROM (
        //             SELECT
        //                 tmp_AP_$this->logError.codigoIps,
        //                 tmp_AP_$this->logError.codigoProcedimiento,
        //                 tmp_AP_$this->logError.numeroFactura,
        //                 tmp_AP_$this->logError.codigoProcedimiento,
        //                 tmp_AP_$this->logError.nr
        //             FROM tmp_AP_$this->logError
        //                 LEFT JOIN refCups ON refCups.codigo = tmp_AP_$this->logError.codigoProcedimiento
        //                 LEFT JOIN ripsAF ON ripsAF.codigoIps = tmp_AP_$this->logError.codigoIps AND
        //                     ripsAF.numeroFactura = tmp_AP_$this->logError.numeroFactura
        //                 LEFT JOIN maestroRedTarifas on maestroRedTarifas.idCtro = ripsAF.numeroContrato AND
        //                     maestroRedTarifas.codigo = tmp_AP_$this->logError.codigoProcedimiento
        //             WHERE (
        //                 maestroRedTarifas.id IS NULL AND refCups.lInf != '' AND
        //                 !(tmp_AP_$this->logError.valorProcedimiento BETWEEN refCups.lInf AND refCups.lSup) ) OR
        //                 ( maestroRedTarifas.id IS NOT NULL AND
        //                 tmp_AP_$this->logError.valorProcedimiento > maestroRedTarifas.valor )
        //             ) as error;"
        // );
    }
}
