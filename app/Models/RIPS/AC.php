<?php

namespace App\Models\RIPS;

use App\Validador\Fechas;
use Illuminate\Support\Facades\DB;

/**
 * Archivo de consulta
 */

class AC extends RIPS implements IRips
{
    public string $numeroFactura = '';
    public string $codigoIps = '';
    public string $tipoIdentificacion = '';
    public string $identificacion = '';
    public string $fechaConsulta = '';
    public int $numeroAutorizacion = 0;
    public string $codigoConsulta = '';
    public string $finalidadConsulta = '';
    public string $codigoCausaExterna = '';
    public string $diagnosticoPrincipal = '';
    public string $diagnostico1 = '';
    public string $diagnostico2 = '';
    public string $diagnostico3 = '';
    public string $tipoDiagnosticoPrincipal = '';
    public string $valorConsulta = '';
    public string $copago = '';
    public string $valorNeto = '';
    protected int $id = 0;
    protected string $nombreTabla = '';
    protected string $logsError = '';

    public static function obtenerColumnasDB(bool $array = false): string | array
    {
        $columnas = 'numeroFactura,' .
            'codigoIps,' .
            'tipoIdentificacion,' .
            'identificacion,' .
            'fechaConsulta,' .
            'numeroAutorizacion,' .
            'codigoConsulta,' .
            'finalidadConsulta,' .
            'codigoCausaExterna,' .
            'diagnosticoPrincipal,' .
            'diagnostico1,' .
            'diagnostico2,' .
            'diagnostico3,' .
            'tipoDiagnosticoPrincipal,' .
            'valorConsulta,' .
            'copago,' .
            'valorNeto';

        if ($array)
        {
            return explode(',', $columnas);
        }

        return $columnas;
    }

    public function tipoRIPS(): string
    {
        return 'AC';
    }

    public function agregarDatos(array $datos)
    {
        $atributos = $this->obtenerColumnasDB(true);

        try
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
        catch (\Throwable $th)
        {
            return 0;
        }
    }

    public function obtenerDatos(bool $string = false): array | string
    {
        $atributos = $this->obtenerColumnasDB(true);
        $salidaArray = array();
        $salidaString = '';
        $salidaString = '';

        foreach ($atributos as $clave)
        {
            if ($string)
            {
                $salidaString .= $this->{$clave} . ',';
            }

            if (!$string)
            {
                if ($string)
                {
                    $salidaString .= $this->{$clave} . ',';
                }

                if (!$string)
                {
                    $salidaArray["$clave"] = $this->{$clave};
                }
            }

            if ($string)
            {
                return substr($salidaString, 0, -1);
            }
        }

        if ($string)
        {
            return substr($salidaString, 0, -1);
        }

        return $salidaArray;
    }

    public function crearTablas(string $nombreTabla)
    {

        $this->nombreTabla = $nombreTabla;
        return DB::statement("CREATE TABLE IF NOT EXISTS tmp_AC_$this->nombreTabla (
            numeroFactura varchar(20) NOT NULL DEFAULT '',
            codigoIps varchar(20) NOT NULL DEFAULT '',
            tipoIdentificacion char(2) NOT NULL DEFAULT '',
            identificacion varchar(20) NOT NULL DEFAULT '',
            fechaConsulta date NOT NULL DEFAULT '0000-01-01',
            numeroAutorizacion int NOT NULL,
            codigoConsulta varchar(8) NOT NULL DEFAULT '',
            finalidadConsulta char(2) NOT NULL DEFAULT '',
            codigoCausaExterna char(2) NOT NULL DEFAULT '',
            diagnosticoPrincipal varchar(4) NOT NULL DEFAULT '',
            diagnostico1 varchar(4) NOT NULL,
            diagnostico2 varchar(4) NOT NULL,
            diagnostico3 varchar(4) NOT NULL,
            tipoDiagnosticoPrincipal char(1) NOT NULL DEFAULT '',
            valorConsulta varchar(12) NOT NULL DEFAULT '',
            copago varchar(12) NOT NULL DEFAULT '',
            valorNeto varchar(12) NOT NULL DEFAULT '',
            nr integer  NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (nr, numeroFactura)
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
            array_push($values, $this->obtenerDatos());
        }

        try
        {
            return DB::table('tmp_AC_' . $this->nombreTabla)->insertOrIgnore($values);
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
        ));
    }

    public function auditar()
    {

        $tablaError = 'tmp_logs_error_' . $this->nombreTabla;

        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', codigoConsulta,
                ' no pertenece al codigo de laconsulta, error en la linea: ', nr,
                ' del archivo AC'
                ),
                'AC'
                FROM (
                    SELECT
                        tmp_AC_$this->nombreTabla.codigoConsulta,
                        tmp_AC_$this->nombreTabla.nr
                    FROM tmp_AC_$this->nombreTabla
                    LEFT JOIN refCups ON refCups.codigo=tmp_AC_$this->nombreTabla.codigoConsulta
                    WHERE refCups.descrip is null  or refCups.AT != 'C'
                ) as error;"
        );

        //!fi & ff refcups 532

        //551
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'El codigo ', codigoIps,
                    ' no pertenece a una IPS registrada, error en la linea: ',
                    nr, ' del archivo AC'
                    ),
                'AC'
                FROM (
                    SELECT
                        tmp_AC_$this->nombreTabla.codigoIps,
                        tmp_AC_$this->nombreTabla.nr
                    FROM tmp_AC_$this->nombreTabla
                    LEFT JOIN refIps ON refIps.codigo=tmp_AC_$this->nombreTabla.codigoIps and
                    refIps.serHab like '%356%' 
                    WHERE tmp_AC_$this->nombreTabla.codigoConsulta in ('890202','890302') and
                    refIps.descrip is null
                ) as error;
        "
        );

        //569
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'El codigo ', finalidadConsulta,
                    ' no corresponde, error en la linea: ',
                    nr, ' del archivo AC'
                    ),
                'AC'
                FROM (
                    SELECT
                        tmp_AC_$this->nombreTabla.finalidadConsulta,
                        tmp_AC_$this->nombreTabla.nr
                    FROM tmp_AC_$this->nombreTabla
                    LEFT JOIN refFinalidadConsulta ON 
                    refFinalidadConsulta.codigo=tmp_AC_$this->nombreTabla.finalidadConsulta
                    WHERE refFinalidadConsulta.codigo is NULL
                ) as error;"
        );

        //588
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'Error en la linea: ', nr,
                    ' del archivo AP, el diagnostico ', finalidadConsulta,
                    ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                    ),
                'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.finalidadConsulta,
                    tmp_AC_$this->nombreTabla.tipoIdentificacion,
                    tmp_AC_$this->nombreTabla.identificacion,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refFinalidadConsulta ON
                refFinalidadConsulta.codigo=tmp_AC_$this->nombreTabla.finalidadConsulta
                LEFT JOIN tmp_US_$this->nombreTabla ON 
                tmp_US_$this->nombreTabla.tipoIdentificacion=tmp_AC_$this->nombreTabla.tipoIdentificacion and 
                tmp_US_$this->nombreTabla.identificacion=tmp_AC_$this->nombreTabla.identificacion
                WHERE (refFinalidadConsulta.genero!= '' and
                refFinalidadConsulta.genero!=tmp_US_$this->nombreTabla.genero)
                ) as error;
        "
        );

        //!wEdad 618

        //647
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', codigocausaExterna,
                ' no corresponde, error en la linea: ', 
                nr, ' del archivo AC'),
                'AC'
                FROM (
                SELECT
                    tmp_AC_$this->nombreTabla.codigoCausaExterna,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCausaExterna ON refCausaExterna.codigo=tmp_AC_$this->nombreTabla.codigoCausaExterna
                WHERE refCausaExterna.descrip is null or tmp_AC_$this->nombreTabla.codigoCausaExterna=''
                ) as error;"
        );

        //665
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', diagnosticoPrincipal,
                ' no corresponde al diagnostico, error en la linea: ',
                nr, ' del archivo AC'
                ),
                'AC'
                FROM (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnosticoPrincipal,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnosticoPrincipal
                WHERE refCie10.descrip is null 
                or tmp_AC_$this->nombreTabla.diagnosticoPrincipal=''
                ) as error;"
        );

        //!wEdad 685

        //715
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'Error en la linea: ',nr,' del archivo AP, el diagnostico ',
                    diagnosticoPrincipal, ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                ),
                'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnosticoPrincipal,
                    tmp_AC_$this->nombreTabla.tipoIdentificacion,
                    tmp_AC_$this->nombreTabla.identificacion,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                    LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnosticoPrincipal
                    LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_AC_$this->nombreTabla.tipoIdentificacion 
                    and ripsUS.identificacion=tmp_AC_$this->nombreTabla.identificacion
                WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero)
                ) as error;
        "
        );

        //743
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'El codigo ', diagnostico1,
                    ' no pertenece al diagnostico, error en la linea: ', 
                    nr, ' del archivo AC'),
                'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico1,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico1
                WHERE refCie10.descrip is null and tmp_AC_$this->nombreTabla.diagnostico1!=''
                ) as error;"
        );

        //! wEdad 763

        //793
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'Error en la linea: ',nr,
                    ' del archivo AC, el diagnostico ',diagnostico1,
                    ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                ),'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico1,
                    tmp_AC_$this->nombreTabla.tipoIdentificacion,
                    tmp_AC_$this->nombreTabla.identificacion,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                    LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico1
                    LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_AC_$this->nombreTabla.tipoIdentificacion 
                    and ripsUS.identificacion=tmp_AC_$this->nombreTabla.identificacion
                WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) 
                and tmp_AC_$this->nombreTabla.diagnostico1!=''
                ) as error;"
        );

        //821
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('
                El codigo ', diagnostico2,
                ' no pertenece al diagnostico, error en la linea: ', 
                nr, ' del archivo AC'
                ),'AC'
                FROM (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico2,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico2
                WHERE refCie10.descrip is null and tmp_AC_$this->nombreTabla.diagnostico2!=''
                ) as error;"
        );

        //! wEdad 841

        //871
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT(
                    'Error en la linea: ', nr,
                      ' del archivo AC, el diagnostico ', diagnostico2,
                    ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                    ), 'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico2,
                    tmp_AC_$this->nombreTabla.tipoIdentificacion,
                    tmp_AC_$this->nombreTabla.identificacion,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico2
                LEFT JOIN tmp_US_$this->nombreTabla 
                on tmp_US_$this->nombreTabla.tipoIdentificacion=tmp_AC_$this->nombreTabla.tipoIdentificacion
                and tmp_US_$this->nombreTabla.identificacion=tmp_AC_$this->nombreTabla.identificacion
                WHERE (refCie10.genero!= '' and refCie10.genero!=tmp_US_$this->nombreTabla.genero) 
                and tmp_AC_$this->nombreTabla.diagnostico2!=''
                ) as error;"
        );

        //899
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('El codigo ', diagnostico3, 
                ' no pertenece al diagnostico, error en la linea: ', 
                nr, ' del archivo AC'), 'AC'
                FROM (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico3,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico3
                WHERE refCie10.descrip is null and tmp_AC_$this->nombreTabla.diagnostico3!=''
                ) as error;"
        );

        //!wEdad 919

        //948
        DB::statement(
            query: "INSERT INTO $tablaError (contenido, tipo)
            select 
                CONCAT('Error en la linea: ', nr,
                    ' del archivo AC, el diagnostico ', diagnostico3,
                    ' no se relaciona con la identificación (',
                    tipoIdentificacion, ') ', identificacion
                    ), 'AC'
                FROM
                (
                SELECT
                    tmp_AC_$this->nombreTabla.diagnostico3,
                    tmp_AC_$this->nombreTabla.tipoIdentificacion,
                    tmp_AC_$this->nombreTabla.identificacion,
                    tmp_AC_$this->nombreTabla.nr
                FROM tmp_AC_$this->nombreTabla
                LEFT JOIN refCie10 ON refCie10.codigo=tmp_AC_$this->nombreTabla.diagnostico3
                LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_AC_$this->nombreTabla.tipoIdentificacion 
                and ripsUS.identificacion=tmp_AC_$this->nombreTabla.identificacion
                WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) 
                and tmp_AC_$this->nombreTabla.diagnostico3!=''
                ) as error;"
        );
        //!maestroRedTarifas no existe 978
    }
}
