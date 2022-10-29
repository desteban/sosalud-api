/* VALIDACION DEL ARCHIVO DE RIPS AF */

SELECT * FROM tmp_af
LEFT JOIN refIps ON refIps.codigo=tmp_af.codigoIps and refIps.tipoIdentificacion=tmp_af.tipoIdentificacion and refIps.identificacion=tmp_af.identificacion
WHERE refIps.tipoIdentificacion is null;

SELECT * FROM tmp_af
LEFT JOIN refIps ON tmp_af.codigoIps=refIps.codigo
WHERE refIps.codigo is NULL;

SELECT * FROM tmp_af
LEFT JOIN refRegimen on refRegimen.codEapb = tmp_af.codigoEapb
WHERE refRegimen.codigo IS NULL;



/* VALIDACION DEL ARCHIVO DE RIPS AP */

SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
WHERE refCups.descrip is null;

******SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
WHERE refCups.descrip is not null and !(tmp_ap.fechaProcedimiento between refCups.fi and refCups.ff);

SELECT * FROM tmp_ap			
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento 
WHERE refCups.descrip is not null and refCups.AT != 'P' ;

SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsus on ripsus.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsus.identificacion=tmp_ap.identificacion
WHERE (refCups.genero!= 'A' and refCups.genero!=ripsus.genero);


SELECT * FROM tmp_ap
LEFT JOIN refFinalidadProcedimiento ON tmp_ap.finalidadProcedimiento=refFinalidadProcedimiento.codigo
WHERE refFinalidadProcedimiento.codigo is NULL;


SELECT * FROM tmp_ap
LEFT JOIN refPersonalAtiende ON tmp_ap.personalAtiende=refPersonalAtiende.codigo
WHERE ('721001' <= tmp_ap.codigoProcedimiento and tmp_ap.codigoProcedimiento <= '740300') and refPersonalAtiende.codigo is NULL; // codigo de parto y no cesarea


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.codigoProcedimiento <= '870000' and refCie10.descrip is null ;


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico
WHERE tmp_ap.diagnostico != '' and refCie10.descrip is null ;


******SELECT * FROM tmp_ap.nR,'$cade' as tA,concat(tmp_ap.diagnostico,' - (',refCie10.eMin,') ',$wEdad,' (',refCie10.eMax,') Error en diagnostico principal con referencia a la edad del maestro de afiliados') as descrip
LEFT JOIN refCie10 as b ON b.codigo = a.diagnostico
LEFT JOIN maestroIdentificaciones 	as e ON maestroIdentificaciones.tipoIdentificacion=a.tipoIdentificacion and maestroIdentificaciones.identificacion=a.identificacion
LEFT JOIN maestroAfiliados      	as c ON maestroAfiliados.numeroCarnet = e.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax)",");

			
SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnostico
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON tmp_ap.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ap.diagnostico1!='';


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ap.diagnostico1!='';


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ap.diagnostico1!='';


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion 
WHERE tmp_ap.diagnosticoComplicacion!='' and refCie10.descrip is null;


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo = tmp_ap.diagnosticoComplicacion
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ap.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ap.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE !(refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax);


SELECT * FROM tmp_ap
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ap.diagnosticoComplicacion
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ap.tipoIdentificacion and ripsUS.identificacion=tmp_ap.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);


SELECT * FROM tmp_ap
LEFT JOIN refActoQuirurgico ON refActoQuirurgico.codigo = tmp_ap.actoQuirurgico
WHERE tmp_ap.actoQuirurgico!='' and refActoQuirurgico.descrip is null ;


******SELECT * FROM tmp_ap
LEFT JOIN refCups ON refCups.codigo = tmp_ap.codigoProcedimiento
LEFT JOIN ripsAF ON ripsAF.codigoIps = tmp_ap.codigoIps and ripsAF.numeroFactura = tmp_ap.numeroFactura
LEFT JOIN maestroRedTarifas on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ap.codigoProcedimiento;
WHERE 	(maestroRedTarifas.id IS NULL AND refCups.lInf != '' AND !(tmp_ap.valorProcedimiento BETWEEN refCups.lInf AND refCups.lSup) )
OR (maestroRedTarifas.id IS NOT NULL AND tmp_ap.valorProcedimiento > maestroRedTarifas.valor);




/* VALIDACION DEL ARCHIVO DE RIPS AC */

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo=tmp_ac.codigoConsulta
WHERE refCups.descrip is null  or refCups.AT != 'C';

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo = tmp_ac.codigoConsulta
WHERE refCups.descrip is not null and !(tmp_ac.fechaConsulta between refCups.fi and refCups.ff) ;

SELECT * FROM tmp_ac
LEFT JOIN refIps ON refIps.codigo=tmp_ac.codigoIps and refIps.serHab like '%356%' 
WHERE tmp_ac.codigoConsulta in ('890202','890302') and refIps.descrip is null  ;

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
WHERE refFinalidadConsulta.codigo is NULL;

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refFinalidadConsulta.genero!= '' and refFinalidadConsulta.genero!=ripsUS.genero);

SELECT * FROM tmp_ac
LEFT JOIN refFinalidadConsulta ON refFinalidadConsulta.codigo=tmp_ac.finalidadConsulta
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refFinalidadConsulta.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refFinalidadConsulta.eMax);

SELECT * FROM tmp_ac
LEFT JOIN refCausaExterna ON refCausaExterna.codigo=tmp_ac.codigoCausaExterna
WHERE refCausaExterna.descrip is null or tmp_ac.codigoCausaExterna='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
WHERE refCie10.descrip is null or tmp_ac.diagnosticoPrincipal='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados  ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax);

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnosticoPrincipal
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero);

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
WHERE refCie10.descrip is null and tmp_ac.diagnostico1!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico1!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico1
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico1!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
WHERE refCie10.descrip is null and tmp_ac.diagnostico2!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico2!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico2
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico2!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
WHERE refCie10.descrip is null and tmp_ac.diagnostico3!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 as b ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ac.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ac.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= refCie10.eMax) and tmp_ac.diagnostico3!='';

SELECT * FROM tmp_ac
LEFT JOIN refCie10 ON refCie10.codigo=tmp_ac.diagnostico3
LEFT JOIN ripsUS on ripsUS.tipoIdentificacion=tmp_ac.tipoIdentificacion and ripsUS.identificacion=tmp_ac.identificacion
WHERE (refCie10.genero!= '' and refCie10.genero!=ripsUS.genero) and tmp_ac.diagnostico3!='';

SELECT * FROM tmp_ac
LEFT JOIN refCups ON refCups.codigo = tmp_ac.valorConsulta
LEFT JOIN ripsAF as c ON ripsAF.codigoIps = tmp_ac.codigoIps and ripsAF.numeroFactura = tmp_ac.numeroFactura
LEFT JOIN maestroRedTarifas as d on maestroRedTarifas.idCtro = ripsAF.numeroContrato and maestroRedTarifas.codigo = tmp_ac.codigoConsulta
WHERE (maestroRedTarifas.id IS NULL AND refCups.lInf != '' and !(tmp_ac.valorConsulta between refCups.lInf and refCups.lSup)) OR (maestroRedTarifas.id IS NOT NULL AND tmp_ac.valorConsulta > maestroRedTarifas.valor);




/* VALIDACION DEL ARCHIVO DE RIPS AH */

SELECT * FROM tmp_ah
LEFT JOIN refViasacceso ON tmp_ah.codigoViasacceso=refViasacceso.codigo
WHERE refViasacceso.descrip is null;

SELECT * FROM tmp_ah
LEFT JOIN refCausaExterna ON tmp_ah.codigoCausaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null and tmp_ah.codigoCausaExterna!='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax);

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoIngreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoIngreso='';

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
WHERE refCie10.descrip is null or tmp_ah.diagnosticoEgreso='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnosticoEgreso=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnosticoEgreso!='' ;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico1!='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico1=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico1!='';

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico2!='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico2=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico2!='' ;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico3=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.diagnostico3!='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.diagnostico3=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.diagnostico3!='' ;

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.codigoComplicacion=refCie10.codigo
WHERE refCie10.descrip is null and tmp_ah.codigoComplicacion!='';

******SELECT * FROM tmp_ah
LEFT JOIN refCie10 as b ON a.codigoComplicacion=refCie10.codigo
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_ah.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_ah.identificacion
LEFT JOIN maestroAfiliados ON maestroAfiliados.numeroCarnet = maestroIdentificaciones.numeroCarnet;
WHERE not (refCie10.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= refCie10.eMax) and tmp_ah.codigoComplicacion!='';

SELECT * FROM tmp_ah
LEFT JOIN refCie10 ON tmp_ah.causaMuerte=refCie10.codigo
WHERE (refCie10.descrip is null and tmp_ah.estadoSalida='2') and tmp_ah.estadoSalida='2';



/* VALIDACION DEL ARCHIVO DE RIPS AM */

SELECT * FROM tmp_AM
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AM.codigoIps and tmp_af.numeroFactura=tmp_AM.numeroFactura
WHERE tmp_af.codigoIps is NULL;

SELECT * FROM tmp_AM
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AM.tipoIdentificacion and tmp_us.identificacion=tmp_AM.identificacion
WHERE tmp_us.identificacion is NULL;

SELECT * FROM tmp_am
LEFT JOIN refCums ON refCums.codigo=tmp_am.codigoMedicamento
WHERE refCums.codigo is null;



/* VALIDACION DEL ARCHIVO DE RIPS AN */

SELECT * FROM tmp_AN 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AN.codigoIps and tmp_af.numeroFactura=tmp_AN.numeroFactura
WHERE tmp_af.codigoIps is NULL;

SELECT * FROM tmp_AN
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AN.tipoIdentificacion and tmp_us.identificacion=tmp_AN.identificacion
WHERE tmp_us.identificacion is NULL;

SELECT * FROM tmp_AN 
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AN.fechaNacimiento and tmp_AN.fechaNacimiento <= tmp_af.fechaFinal);

SELECT * FROM tmp_an
LEFT JOIN refCie10 ON tmp_an.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null or tmp_an.diagnostico='';

SELECT * FROM tmp_an
LEFT JOIN refCie10 ON tmp_an.diagnosticoMuerte=refCie10.codigo
WHERE tmp_an.diagnosticoMuerte!='' and refCie10.descrip is null;



/* VALIDACION DEL ARCHIVO DE RIPS AT */

SELECT * FROM tmp_AT 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AT.codigoIps and tmp_af.numeroFactura=tmp_AT.numeroFactura
WHERE tmp_af.codigoIps is NULL;

SELECT * FROM tmp_AT
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AT.tipoIdentificacion and tmp_us.identificacion=tmp_AT.identificacion
WHERE tmp_us.identificacion is NULL;

SELECT * FROM tmp_at
LEFT JOIN refCups ON refCups.codigo=tmp_at.codigoServicio
WHERE !( (ifnull(refCups.AT,'')=tmp_at.tipoServicio) or (tmp_at.tipoServicio='1' and refcups.AT is NULL) );

SELECT * FROM tmp_at
LEFT JOIN refCups on refCups.codigo = tmp_at.codigoServicio
WHERE refCups.codigo is not null and refCups.lSup != 0 and  tmp_at.valorUnitario > refCups.lSup;



/* VALIDACION DEL ARCHIVO DE RIPS AU */

SELECT * FROM tmp_AU 
LEFT JOIN tmp_af ON tmp_af.codigoIps = tmp_AU.codigoIps and tmp_af.numeroFactura=tmp_AU.numeroFactura
WHERE tmp_af.codigoIps is NULL;

SELECT * FROM tmp_AU
LEFT JOIN tmp_us ON tmp_us.tipoIdentificacion=tmp_AU.tipoIdentificacion and tmp_us.identificacion=tmp_AU.identificacion
WHERE tmp_us.identificacion is NULL;

SELECT * FROM tmp_AU 
LEFT JOIN tmp_af using(codigoIps,numeroFactura)
WHERE !(tmp_af.fechaInicio <= tmp_AU.fechaSalida and tmp_AU.fechaSalida <= tmp_af.fechaFinal);

SELECT * FROM tmp_au
LEFT JOIN refCausaExterna ON tmp_au.causaExterna=refCausaExterna.codigo
WHERE refCausaExterna.descrip is null;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico=refCie10.codigo
WHERE refCie10.descrip is null;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico1=refCie10.codigo
WHERE tmp_au.diagnostico1!='' and refCie10.descrip is null;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico2=refCie10.codigo
WHERE tmp_au.diagnostico2!='' and refCie10.descrip is null;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.diagnostico3=refCie10.codigo
WHERE tmp_au.diagnostico3!='' and refCie10.descrip is null;

SELECT * FROM tmp_au
LEFT JOIN refCie10 ON tmp_au.causaMuerte=refCie10.codigo
WHERE tmp_au.estadoSalida='2' and (refCie10.descrip is null or tmp_au.causaMuerte='');

SELECT * FROM tmp_au
LEFT JOIN ripsAF using(codigoIps,numeroFactura)
WHERE !(ripsAF.fechaInicio <= tmp_au.fechaSalida and tmp_au.fechaSalida <= ripsAF.fechaFinal);


/* VALIDACION DEL ARCHIVO DE RIPS US */

SELECT * FROM tmp_US
LEFT JOIN maestroIdentificaciones ON maestroIdentificaciones.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroIdentificaciones.identificacion=tmp_US.identificacion
WHERE maestroIdentificaciones.identificacion is NULL;


SELECT * FROM tmp_US
LEFT JOIN maestroAfiliados on maestroAfiliados.tipoIdentificacion=tmp_US.tipoIdentificacion and maestroAfiliados.identificacion=tmp_US.identificacion
WHERE tmp_US.genero!=maestroAfiliados.genero;


SELECT * FROM tmp_US
LEFT JOIN municipios USING(codigoDepartamento,codigoMunicipio)
WHERE municipios.codigoMunicipio is NULL;


SELECT * FROM tmp_US
LEFT JOIN refZona on refZona.codigo=tmp_US.zona
WHERE refZona.codigo is NULL;
