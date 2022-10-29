CREATE TABLE IF NOT EXISTS tmp_logs_error(
    contenido TEXT NOT NULL,
    tipo VARCHAR(2) NOT NULL COMMENT "El tipo de RIPS"
);

/* VALIDACION DEL ARCHIVO DE RIPS AF */

SELECT * FROM tmp_af
LEFT JOIN refIps ON refIps.codigo=tmp_af.codigoIps and refIps.tipoIdentificacion=tmp_af.tipoIdentificacion and refIps.identificacion=tmp_af.identificacion
WHERE refIps.tipoIdentificacion is null;

INSERT INTO tmp_logs_error (contenido, tipo)
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
                tmp_AF.codigoIps,
                tmp_AF.nr,
                tmp_AF.tipoIdentificacion,
                tmp_AF.identificacion
            FROM tmp_AF
                LEFT JOIN refIps ON refIps.codigo=tmp_Af.codigoIps and
                refIps.tipoIdentificacion=tmp_AF.tipoIdentificacion and
                refIps.identificacion=tmp_af.identificacion
            WHERE refIps.tipoIdentificacion is null
        ) 
        as error;

SELECT * FROM tmp_af
LEFT JOIN refIps ON tmp_af.codigoIps=refIps.codigo
WHERE refIps.codigo is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoIps, ' no pertenece a una IPS registrada, error en la linea: ', nr, ' del archivo AF'),
        'AF'
        FROM
        (
        SELECT
            tmp_AF.codigoIps,
            tmp_AF.nr
        FROM tmp_AF
        LEFT JOIN refIps ON tmp_af.codigoIps=refIps.codigo
WHERE refIps.codigo is NULL
        ) as error;

SELECT * FROM tmp_af
LEFT JOIN refRegimen on refRegimen.codEapb = tmp_af.codigoEapb
WHERE refRegimen.codigo IS NULL

        INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoEapb, ' no pertenece a una EAPB registrada, error en la linea: ', nr, ' del archivo AF'),
        'AF'
        FROM
        (
        SELECT
            tmp_AF.codigoEapb,
            tmp_AF.nr
        FROM tmp_AF
        refRegimen on refRegimen.codEapb = tmp_af.codigoEapb
WHERE refRegimen.codigo IS NULL
        ) 
        as error;



/* VALIDACION DEL ARCHIVO DE RIPS AP ----------------------------------------------------------*/

SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
WHERE refCups.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoProcedimiento, ' no corresponde a los CUPS registrados, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.codigoProcedimiento,
            tmp_AP.nr
            FROM tmp_AP
            LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
            WHERE refCups.descrip is null
) as error;

******SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
WHERE refCups.descrip is not null and !(tmp_ap.fechaProcedimiento between refCups.fi and refCups.ff);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoProcedimiento, ' no corresponde a los CUPS registrados, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.codigoProcedimiento,
            tmp_AP.nr
            FROM tmp_AP
            LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
            WHERE refCups.descrip is not null and !(tmp_ap.fechaProcedimiento between refCups.fi and refCups.ff)
) as error;

SELECT * FROM tmp_ap			
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento 
WHERE refCups.descrip is not null and refCups.AT != 'P' ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoProcedimiento, ' no corresponde a los CUPS registrados, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.codigoProcedimiento,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento 
WHERE refCups.descrip is not null and refCups.AT != 'P'
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsus on ripsus.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsus.identificacion=tmp_ap.identificacion
WHERE (refCups.genero!= 'A' and refCups.genero!=ripsus.genero);

INSERT INTO tmp_logs_error (contenido, tipo)
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
            tmp_ap.codigoProcedimiento,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsus on ripsus.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsus.identificacion=tmp_ap.identificacion
WHERE (refCups.genero!= 'A' and refCups.genero!=ripsus.genero)
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refFinalidadProcedimiento ON tmp_ap.finalidadProcedimiento=refFinalidadProcedimiento.codigo
WHERE refFinalidadProcedimiento.codigo is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', finalidadProcedimiento, ' no corresponde al procedimiento, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.finalidadProcedimiento,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refFinalidadProcedimiento ON tmp_ap.finalidadProcedimiento=refFinalidadProcedimiento.codigo
WHERE refFinalidadProcedimiento.codigo is NULL
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refPersonalAtiende ON tmp_ap.personalAtiende=refPersonalAtiende.codigo
WHERE ('721001' <= tmp_ap.codigoProcedimiento and tmp_ap.codigoProcedimiento <= '740300') and refPersonalAtiende.codigo is NULL; // codigo de parto y no cesarea

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', personalAtiende, ' no coincide, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.personalAtiende,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refPersonalAtiende ON tmp_ap.personalAtiende=refPersonalAtiende.codigo
WHERE ('721001' <= tmp_ap.codigoProcedimiento and tmp_ap.codigoProcedimiento <= '740300') and refPersonalAtiende.codigo is NULL; // codigo de parto y no cesarea
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.codigoProcedimiento <= '870000' and refCie10.descrip is null ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.codigoProcedimiento <= '870000' and refCie10.descrip is null
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.diagnostico != '' and refCie10.descrip is null ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCATCONCAT('El codigo ', diagnostico, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.diagnostico != '' and refCie10.descrip is null
) as error;

******SELECT * FROM tmp_ap.nR
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)",";

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT (tmp_ap.diagnostico,' - (',refCie10.eMin,') ',$wEdad,' (',refCie10.eMax,') Error en diagnostico principal con referencia a la edad del maestro de afiliados'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)",";
) as error;
        
SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnostico
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);

INSERT INTO tmp_logs_error (contenido, tipo)
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
            tmp_ap.diagnostico,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnostico
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero)
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON tmp_ap.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ap.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico1, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico1,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON tmp_ap.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ap.diagnostico1!=''
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ap.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico1 ',
            diagnostico,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico1,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ap.diagnostico1!=''
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ap.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico2 ',
            diagnostico,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnostico1,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ap.diagnostico1!=''
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion 
WHERE tmp_ap.diagnosticoComplicacion!='' and refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnosticoComplicacion, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AP'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnosticoComplicacion,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion 
WHERE tmp_ap.diagnosticoComplicacion!='' and refCie10.descrip is null
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            diagnosticoComplicacion,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnosticoComplicacion,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnosticoComplicacion
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            diagnosticoComplicacion,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.diagnosticoComplicacion,
            tmp_ap.tipoIdentificacion
            tmp_ap.identificacion
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnosticoComplicacion
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero)
) as error;

SELECT * FROM tmp_ap
LEFT JOIN refActoQuirurgico ON refActoQuirurgico.codigo = tmp_ap.actoQuirurgico
WHERE tmp_ap.actoQuirurgico!='' and refActoQuirurgico.descrip is null ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoIps, ' no pertenece a una IPS registrada, error en la linea: ', nr, ' del archivo AF'),
        'AP'
        FROM
        (
        SELECT
            tmp_ap.actoQuirurgico,
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refActoQuirurgico ON refActoQuirurgico.codigo = tmp_ap.actoQuirurgico
WHERE tmp_ap.actoQuirurgico!='' and refActoQuirurgico.descrip is null
) as error;

******SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsAF ON ripsAF.codigoIps = tmp_ap.codigoIps and ripsAF.numeroFactura = tmp_ap.numeroFactura
LEFT JOIN maestroRedTarifas on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ap.codigoProcedimiento;
WHERE 	(maestroRedTarifas.id IS NULL AND refCups.lInf != '' AND !(tmp_ap.valorProcedimiento BETWEEN refCups.lInf AND refCups.lSup) )
OR (maestroRedTarifas.id IS NOT NULL AND tmp_ap.valorProcedimiento > maestroRedTarifas.valor);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el codigo diagnostico ',
            codigoIps, codigoProcedimiento,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AP'
        (
        SELECT
            tmp_AP.codigoIps,
            tmp_ap.codigoProcedimiento
            tmp_ap.numeroFactura
            tmp_ap.codigoProcedimiento
            tmp_AP.nr
            FROM tmp_AP
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsAF ON ripsAF.codigoIps = tmp_ap.codigoIps and ripsAF.numeroFactura = tmp_ap.numeroFactura
LEFT JOIN maestroRedTarifas on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ap.codigoProcedimiento;
WHERE 	(maestroRedTarifas.id IS NULL AND refCups.lInf != '' AND !(tmp_ap.valorProcedimiento BETWEEN refCups.lInf AND refCups.lSup) )
OR (maestroRedTarifas.id IS NOT NULL AND tmp_ap.valorProcedimiento > maestroRedTarifas.valor)
) as error;










/* VALIDACION DEL ARCHIVO DE RIPS AC */

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo=tmp_ac.codigoConsulta
WHERE refCups.descrip is null  or refCups.AT != 'C';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoConsulta, ' no pertenece al codigo de laconsulta, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.codigoConsulta,
            tmp_ac.nr
            FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo=tmp_ac.codigoConsulta
WHERE refCups.descrip is null  or refCups.AT != 'C'
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo = tmp_ac.codigoConsulta
WHERE refCups.descrip is not null and !(tmp_ac.fechaConsulta between refCups.fi and refCups.ff) ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('El codigo ', codigoConsulta, ' no pertenece al codigo de laconsulta, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.codigoConsulta,
            tmp_ac.nr
            FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo = tmp_ac.codigoConsulta
WHERE refCups.descrip is not null and !(tmp_ac.fechaConsulta between refCups.fi and refCups.ff)
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refIps ON refIps.codigo=tmp_ac.codigoIps and refIps.serHab like '%356%' 
WHERE tmp_ac.codigoConsulta in ('890202','890302') and refIps.descrip is null  ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoIps, ' no pertenece a una IPS registrada, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.codigoIps,
            tmp_ac.nr
            FROM tmp_ac
LEFT JOIN refIps ON refIps.codigo=tmp_ac.codigoIps and refIps.serHab like '%356%' 
WHERE tmp_ac.codigoConsulta in ('890202','890302') and refIps.descrip is null
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
WHERE refFinalidadConsulta.codigo is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', finalidadConsulta, ' no corresponde, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.finalidadConsulta,
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
WHERE refFinalidadConsulta.codigo is NULL
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refFinalidadConsulta.genero!= '' and refFinalidadConsulta.genero!=ripsUS.genero);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            finalidadConsulta,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.finalidadConsulta,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refFinalidadConsulta.genero!= '' and refFinalidadConsulta.genero!=ripsUS.genero)
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refFinalidadConsulta.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refFinalidadConsulta.eMax);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            finalidadConsulta,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.finalidadConsulta,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refFinalidadConsulta.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refFinalidadConsulta.eMax)
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCausaExterna ON refCausaExterna.codigo=tmp_ac.codigoCausaExterna
WHERE refCausaExterna.descrip is null or tmp_ac.codigoCausaExterna='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigocausaExterna, ' no corresponde, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.codigoCausaExterna,
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCausaExterna ON refCausaExterna.codigo=tmp_ac.codigoCausaExterna
WHERE refCausaExterna.descrip is null or tmp_ac.codigoCausaExterna=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
WHERE refCie10.descrip is null or tmp_ac.diagnosticoPrincipal='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnosticoPrincipal, ' no corresponde al diagnostico, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnosticoPrincipal,
            tmp_AP.nr
            FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
WHERE refCie10.descrip is null or tmp_ac.diagnosticoPrincipal=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados  ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            diagnosticoPrincipal,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnosticoPrincipal,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_Ac.nr
            FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados  ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax)
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AP, el diagnostico ',
            diagnosticoPrincipal,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnosticoPrincipal,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_ac.nr
            FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero)
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
WHERE refCie10.descrip is null and tmp_ac.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico1, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico1,
            tmp_AC.nr
            FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
WHERE refCie10.descrip is null and tmp_ac.diagnostico1!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnosti1,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico1,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico1!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnostico1,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico1,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico1!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
WHERE refCie10.descrip is null and tmp_ac.diagnostico2!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico2, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico2,
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
WHERE refCie10.descrip is null and tmp_ac.diagnostico2!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico2!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnostico2,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico2,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico2!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico2!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnostico2,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico2,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico2!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
WHERE refCie10.descrip is null and tmp_ac.diagnostico3!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico3, ' no pertenece al diagnostico, error en la linea: ', nr, ' del archivo AC'),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico3,
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
WHERE refCie10.descrip is null and tmp_ac.diagnostico3!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico3!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnostico3,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico3,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico3!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico3!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('Error en la linea: ',
             nr,
              ' del archivo AC, el diagnostico ',
            diagnostico3,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AC'
        FROM
        (
        SELECT
            tmp_ac.diagnostico3,
            tmp_ac.tipoIdentificacion
            tmp_ac.identificacion
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico3!=''
) as error;

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo = tmp_ac.valorConsulta
LEFT JOIN ripsAF as c ON ripsAF.codigoIps = tmp_ac.codigoIps and ripsAF.numeroFactura = tmp_ac.numeroFactura
LEFT JOIN maestroRedTarifas as d on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ac.codigoConsulta
WHERE (maestroRedTarifas.id IS NULL AND refCups.lInf != '' and !(tmp_ac.valorConsulta between refCups.lInf and refCups.lSup)) OR (maestroRedTarifas.id IS NOT NULL AND tmp_ac.valorConsulta > maestroRedTarifas.valor);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoIps, ' no pertenece a una IPS registrada, error en la linea: ', nr, ' del archivo AF'),
        'AF'
        FROM
        (
        SELECT
            tmp_AC.codigoIps,
            tmp_ac.valorConsulta
            tmp_ac.numeroFactura
            tmp_ac.codigoConsulta
            tmp_AC.nr
            FROM tmp_AC
LEFT JOIN refCups ON refCups.codigo = tmp_ac.valorConsulta
LEFT JOIN ripsAF as c ON ripsAF.codigoIps = tmp_ac.codigoIps and ripsAF.numeroFactura = tmp_ac.numeroFactura
LEFT JOIN maestroRedTarifas on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ac.codigoConsulta
WHERE (maestroRedTarifas.id IS NULL AND refCups.lInf != '' and !(tmp_ac.valorConsulta between refCups.lInf and refCups.lSup)) OR (maestroRedTarifas.id IS NOT NULL AND tmp_ac.valorConsulta > maestroRedTarifas.valor)
) as error;












/* VALIDACION DEL ARCHIVO DE RIPS AH */

SELECT * FROM tmp_ah
LEFT JOIN refViasacceso ON tmp_ah.codigoViasacceso=refViasacceso.codigo
WHERE refViasacceso.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoViasacceso, ' no pertenece a la via de acceso, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.codigoViasacceso,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refViasacceso ON tmp_ah.codigoViasacceso=refViasacceso.codigo
WHERE refViasacceso.descrip is null
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCausaExterna ON tmp_ah.codigoCausaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null and tmp_ah.codigoCausaExterna!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoCausaExterna, ' no corresponde a la causa externa, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.codigoCausaExterna,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCausaExterna ON tmp_ah.codigoCausaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null and tmp_ah.codigoCausaExterna!=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el diagnostico ',
            diagnosticoIngreso,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnosticoIngreso,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax)
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoIngreso='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo 'diagnosticoIngreso, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnosticoIngreso,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoIngreso=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoEgreso='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo 'diagnosticoEgreso, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnosticoEgreso,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoEgreso=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnosticoEgreso!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el diagnostico ',
            diagnosticoEgreso,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnosticoEgreso,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnosticoEgreso!=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico1, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico1,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico1!=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico1!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCATCONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el diagnostico ',
            diagnostico1,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico1,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico1!=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico2!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico2, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico2,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico2!=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico2!='' ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el diagnostico ',
            diagnostico2,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico2,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico2!=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico3=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico3!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico3, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico3,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.diagnostico3=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico3!=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 as b ON tmp_ah.diagnostico3=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico3!='' ;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el diagnostico ',
            diagnostico3,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.diagnostico3,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 as b ON tmp_ah.diagnostico3=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico3!=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.codigoComplicacion=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.codigoComplicacion!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoComplicacion, ' no pertenece, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.codigoComplicacion,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.codigoComplicacion=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.codigoComplicacion!=''
) as error;

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.codigoComplicacion=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.codigoComplicacion!='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select CONCAT('Error en la linea: ',
             nr,
              ' del archivo AH, el codigo ',
            codigoComplicacion,
            ' no se relaciona con la identificación (',
            tipoIdentificacion, ') ',
            identificacion
            ),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.codigoComplicacion,
            tmp_ah.tipoIdentificacion
            tmp_ah.identificacion
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 as b ON a.codigoComplicacion=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.codigoComplicacion!=''
) as error;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.causaMuerte=refCie10.codigo
WHERE (refCie10.descrip is null and tmp_ah.estadoSalida='2') and tmp_ah.estadoSalida='2';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', causaMuerte, ' no corresponde con la causa de muerte, error en la linea: ', nr, ' del archivo AH'),
        'AH'
        FROM
        (
        SELECT
            tmp_ah.causaMuerte,
            tmp_AH.nr
            FROM tmp_AH
LEFT JOIN refCie10 ON tmp_ah.causaMuerte=refCie10.codigo
WHERE (refCie10.descrip is null and tmp_ah.estadoSalida='2') and tmp_ah.estadoSalida='2'
) as error;













/* VALIDACION DEL ARCHIVO DE RIPS AM */

SELECT * FROM tmp_AM
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AM.codigoIps and tmp_af.numeroFactura=tmp_AM.numeroFactura
WHERE tmp_af.codigoIps is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AM, el codigo ',
            codigoIps,
            ' no se relaciona con el numero de factura (',
            numeroFactura, ') ',
            ),
        'AM'
        FROM
        (
        SELECT
            tmp_AM.codigoIps,
            tmp_AM.numeroFactura
            tmp_AM.nr
            FROM tmp_AM
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AM.codigoIps and tmp_af.numeroFactura=tmp_AM.numeroFactura
WHERE tmp_af.codigoIps is NULL
) as error;

SELECT * FROM tmp_AM
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AM.tipoIdentificacion and tmp_us.identificacion=tmp_AM.identificacion
WHERE tmp_us.identificacion is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo AM no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo US, error en la linea: ', nr, ' del archivo US'),
        'AM'
        FROM
        (
        SELECT
            tmp_AM.tipoIdentificacion,
            tmp_AM.identificacion
            tmp_AM.nr
            FROM tmp_AM
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AM.tipoIdentificacion and tmp_us.identificacion=tmp_AM.identificacion
WHERE tmp_us.identificacion is NULL
) as error;

SELECT * FROM tmp_am
LEFT JOIN refCums ON refCums.codigo=tmp_am.codigoMedicamento
WHERE refCums.codigo is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoMedicamento, ' no corresponde, error en la linea: ', nr, ' del archivo AM'),
        'AM'
        FROM
        (
        SELECT
            tmp_am.codigoMedicamento,
            tmp_AM.nr
            FROM tmp_AM
LEFT JOIN refCums ON refCums.codigo=tmp_am.codigoMedicamento
WHERE refCums.codigo is null
) as error;















/* VALIDACION DEL ARCHIVO DE RIPS AN */

SELECT * FROM tmp_AN 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AN.codigoIps and tmp_af.numeroFactura=tmp_AN.numeroFactura
WHERE tmp_af.codigoIps is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AN, el codigo ',
            codigoIps,
            ' no se relaciona con el numero de factura (',
            numeroFactura, ') ',
            ),
        'AN'
        FROM
        (
        SELECT
            tmp_AN.codigoIps,
            tmp_AN.numeroFactura
            tmp_AN.nr
            FROM tmp_AN
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AN.codigoIps and tmp_af.numeroFactura=tmp_AN.numeroFactura
WHERE tmp_af.codigoIps is NULL
) as error;

SELECT * FROM tmp_AN
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AN.tipoIdentificacion and tmp_us.identificacion=tmp_AN.identificacion
WHERE tmp_us.identificacion is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo AN no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo US, error en la linea: ', nr, ' del archivo US'),
        'AN'
        FROM
        (
        SELECT
            tmp_AN.tipoIdentificacion,
            tmp_AN.identificacion
            tmp_AN.nr
            FROM tmp_AN
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AN.tipoIdentificacion and tmp_us.identificacion=tmp_AN.identificacion
WHERE tmp_us.identificacion is NULL
) as error;

SELECT * FROM tmp_AN 
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AN.fechaNacimiento and tmp_AN.fechaNacimiento <= tmp_af.fechaFinal);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La fecha de inicio y la fecha final del archivo AF', fechaInicio, fechaFinal, 'no deben ser mayores a la fecha de naciomiento del archivo AN,', fechaNacimiento,' error en la linea: ', nr, ' del archivo AN'),
        'AN'
        FROM
        (
        SELECT
            tmp_AN.fechaNacimiento,
            tmp_AN.nr
            FROM tmp_AN
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AN.fechaNacimiento and tmp_AN.fechaNacimiento <= tmp_af.fechaFinal)
) as error;

SELECT * FROM tmp_an
LEFT JOIN refCie10 ON tmp_an.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null or tmp_an.diagnostico='';

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico, ' no pertenece, error en la linea: ', nr, ' del archivo AN'),
        'AN'
        FROM
        (
        SELECT
            tmp_an.diagnostico,
            tmp_AN.nr
            FROM tmp_AN
LEFT JOIN refCie10 ON tmp_an.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null or tmp_an.diagnostico=''
) as error;


SELECT * FROM tmp_an
LEFT JOIN refCie10 ON tmp_an.diagnosticoMuerte=refCie10.codigo
WHERE tmp_an.diagnosticoMuerte!='' and refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnosticoMuerte, ' no pertenece, error en la linea: ', nr, ' del archivo AN'),
        'AN'
        FROM
        (
        SELECT
            tmp_an.diagnosticoMuerte,
            tmp_AN.nr
            FROM tmp_AN
LEFT JOIN refCie10 ON tmp_an.diagnosticoMuerte=refCie10.codigo
WHERE tmp_an.diagnosticoMuerte!='' and refCie10.descrip is null
) as error;
















/* VALIDACION DEL ARCHIVO DE RIPS AT */

SELECT * FROM tmp_AT 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AT.codigoIps and tmp_af.numeroFactura=tmp_AT.numeroFactura
WHERE tmp_af.codigoIps is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
 CONCAT('Error en la linea: ',
             nr,
              ' del archivo AT, el codigo ',
            codigoIps,
            ' no se relaciona con el numero de factura (',
            numeroFactura, ') ',
            ),
        'AT'
        FROM
        (
        SELECT
            tmp_AT.codigoIps,
            tmp_AT.numeroFactura
            tmp_AT.nr
            FROM tmp_AT
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AT.codigoIps and tmp_af.numeroFactura=tmp_AT.numeroFactura
WHERE tmp_af.codigoIps is NULL
) as error;

SELECT * FROM tmp_AT
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AT.tipoIdentificacion and tmp_us.identificacion=tmp_AT.identificacion
WHERE tmp_us.identificacion is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo AT no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo US, error en la linea: ', nr, ' del archivo US'),
        'AT'
        FROM
        (
        SELECT
            tmp_AT.tipoIdentificacion,
            tmp_AT.identificacion
            tmp_AT.nr
            FROM tmp_AT
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AT.tipoIdentificacion and tmp_us.identificacion=tmp_AT.identificacion
WHERE tmp_us.identificacion is NULL
) as error;

SELECT * FROM tmp_at
LEFT JOIN refCups ON refCups.codigo=tmp_at.codigoServicio
WHERE !( (ifnull(refCups.AT,'')=tmp_at.tipoServicio) or (tmp_at.tipoServicio='1' and refcups.AT is NULL) );

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoServicio, ' no corresponde al servicio, error en la linea: ', nr, ' del archivo AT'),
        'AT'
        FROM
        (
        SELECT
            tmp_at.codigoServicio,
            tmp_AT.nr
            FROM tmp_AT
LEFT JOIN refCups ON refCups.codigo=tmp_at.codigoServicio
WHERE !( (ifnull(refCups.AT,'')=tmp_at.tipoServicio) or (tmp_at.tipoServicio='1' and refcups.AT is NULL) )
) as error;

SELECT * FROM tmp_at
LEFT JOIN refCups on refCups.codigo = tmp_at.codigoServicio
WHERE refCups.codigo is not null and refCups.lSup != 0 and  tmp_at.valorUnitario > refCups.lSup;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoServicio, ' no corresponde al servicio, error en la linea: ', nr, ' del archivo AT'),
        'AT'
        FROM
        (
        SELECT
            tmp_at.codigoServicio,
            tmp_AT.nr
            FROM tmp_AT
LEFT JOIN refCups on refCups.codigo = tmp_at.codigoServicio
WHERE refCups.codigo is not null and refCups.lSup != 0 and  tmp_at.valorUnitario > refCups.lSup
) as error;
















/* VALIDACION DEL ARCHIVO DE RIPS AU */

SELECT * FROM tmp_AU 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AU.codigoIps and tmp_af.numeroFactura=tmp_AU.numeroFactura
WHERE tmp_af.codigoIps is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
CONCAT('Error en la linea: ',
             nr,
              ' del archivo AU, el diagnostico ',
            codigoIps,
            ' no se relaciona con el numero de la factura (',
            numeroFactura, ') ',),
        'AU'
        FROM
        (
        SELECT
            tmp_AU.codigoIps,
            tmp_AU.numeroFactura
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AU.codigoIps and tmp_af.numeroFactura=tmp_AU.numeroFactura
WHERE tmp_af.codigoIps is NULL
) as error;

SELECT * FROM tmp_AU
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AU.tipoIdentificacion and tmp_us.identificacion=tmp_AU.identificacion
WHERE tmp_us.identificacion is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo AU no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo US, error en la linea: ', nr, ' del archivo US'),
        'AU'
        FROM
        (
        SELECT
            tmp_AU.tipoIdentificacion,
            tmp_AU.identificacion
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AU.tipoIdentificacion and tmp_us.identificacion=tmp_AU.identificacion
WHERE tmp_us.identificacion is NULL
) as error;

SELECT * FROM tmp_AU 
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AU.fechaSalida and tmp_AU.fechaSalida <= tmp_af.fechaFinal);

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La fecha de inicio y la fecha final del archivo AF', fechaInicio, fechaSalida, 'no deben ser mayores a la fecha de salida del archivo AU,', fechaSalida,' error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_AU.fechaSalida,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AU.fechaSalida and tmp_AU.fechaSalida <= tmp_af.fechaFinal)
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCausaExterna ON tmp_au.causaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', causaExterna, ' no corresponde a la causa externa, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.causaExterna,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCausaExterna ON tmp_au.causaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico, ' no corresponde al diagnostico, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.diagnostico,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCie10 ON tmp_au.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico1=refCie10.codigo
WHERE tmp_au.diagnostico1!='' and refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico1, ' no corresponde al diagnostico, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.diagnostico1,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCie10 ON tmp_au.diagnostico1=refCie10.codigo
WHERE tmp_au.diagnostico1!='' and refCie10.descrip is null
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico2=refCie10.codigo
WHERE tmp_au.diagnostico2!='' and refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico2, ' no corresponde al diagnostico, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.diagnostico2,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCie10 ON tmp_au.diagnostico2=refCie10.codigo
WHERE tmp_au.diagnostico2!='' and refCie10.descrip is null
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico3=refCie10.codigo
WHERE tmp_au.diagnostico3!='' and refCie10.descrip is null;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', diagnostico3, ' no corresponde al diagnostico, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.diagnostico3,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCie10 ON tmp_au.diagnostico3=refCie10.codigo
WHERE tmp_au.diagnostico3!='' and refCie10.descrip is null
) as error;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.causaMuerte=refCie10.codigo
WHERE tmp_au.estadoSalida='2' and (refCie10.descrip is null or tmp_au.causaMuerte='');

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', causaMuerte, ' no corresponde a la causa de muerte, error en la linea: ', nr, ' del archivo AU'),
        'AU'
        FROM
        (
        SELECT
            tmp_au.causaMuerte,
            tmp_AU.nr
            FROM tmp_AU
LEFT JOIN refCie10 ON tmp_au.causaMuerte=refCie10.codigo
WHERE tmp_au.estadoSalida='2' and (refCie10.descrip is null or tmp_au.causaMuerte='')
) as error;














/* VALIDACION DEL ARCHIVO DE RIPS US */

SELECT * FROM tmp_US
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_US.identificacion
WHERE maestroIdentificaciones.identificacion is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo US no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo maestroIdentificaciones, error en la linea: ', nr, ' del archivo US'),
        'US'    
        FROM
        (
        SELECT
            tmp_US.tipoIdentificacion,
            tmp_US.identificacion
            tmp_US.nr
            FROM tmp_US
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_US.identificacion
WHERE maestroIdentificaciones.identificacion is NULL
) as error;

SELECT * FROM tmp_US
LEFT JOIN maestroAfiliados on maestroAfiliados.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroAfiliados.identificacion=tmp_US.identificacion
WHERE tmp_US.genero!=maestroAfiliados.genero;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,  'del archivo US no coincide con La identificacion y el tipo de identificacion', tipoIdentificacion , identificacion,' del archivo de alrchivo maestroIdentificaciones, error en la linea: ', nr, ' del archivo US'),
        'US'
        FROM
        (
        SELECT
            tmp_US.tipoIdentificacion,
            tmp_US.identificacion
            tmp_US.nr
            FROM tmp_US
LEFT JOIN maestroAfiliados on maestroAfiliados.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroAfiliados.identificacion=tmp_US.identificacion
WHERE tmp_US.genero!=maestroAfiliados.genero
) as error;

SELECT * FROM tmp_US
LEFT JOIN municipios USING(codigoDepartamento,codigoMunicipio)
WHERE municipios.codigoMunicipio is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', codigoMunicipio, ' no se enceuntra registrado, error en la linea: ', nr, ' del archivo AU'),
        'US'
        FROM
        (
        SELECT
           tmp_US.nr
            FROM tmp_US
LEFT JOIN municipios USING(codigoDepartamento,codigoMunicipio)
WHERE municipios.codigoMunicipio is NULL
) as error;

SELECT * FROM tmp_US
LEFT JOIN refZona on refZona.codigo=tmp_US.zona
WHERE refZona.codigo is NULL;

INSERT INTO tmp_logs_error (contenido, tipo)
    select 
        CONCAT('El codigo ', zona, ' no corresponde a la zona, error en la linea: ', nr, ' del archivo US'),
    'US'
        FROM
        (
        SELECT
            tmp_US.zona,
            tmp_US.nr
            FROM tmp_US
LEFT JOIN refZona on refZona.codigo=tmp_US.zona
WHERE refZona.codigo is NULL
) as error;
