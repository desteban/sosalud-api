/* VALIDACION DEL ARCHIVO DE RIPS AC */

SELECT * FROM tmp_AC 
LEFT JOIN AF$arch as b ON b.codigoIps = a.codigoIps and b.numeroFactura=a.numeroFactura;
WHERE b.codigoIps is NULL;
			db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat(a.codigoIps,'-',a.numeroFactura,' - No existe en referencia de AF') as descrip from $cade$arch as a $relacion $condicion","");
		}

SELECT * FROM tmp_AC 
LEFT JOIN US$arch as c ON c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE c.identificacion is NULL;
			db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat(a.tipoIdentificacion,'-',a.identificacion,' No Existe en US') as descrip from $cade$arch as a $relacion $condicion","");
		}

SELECT * FROM tmp_AC 
LEFT JOIN AF$arch as b using(codigoIps,numeroFactura);
WHERE !(b.fechaInicio <= a.fechaConsulta and a.fechaConsulta <= b.fechaFinal);
			db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat(a.fechaConsulta,' Error en la fecha de Consulta, fuera del periodo de facturacion') as descrip from $cade$arch as a $relacion $condicion","");
		}




/* AUTORIZACIONES: invalida) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and b.id is null;
		db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (NO EXISTE NUMERO)') as descrip from $cade$arch as a $relacion $condicion","");



/* AUTORIZACIONES: anuluda) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(d.ind='1' and b.ind='1');
		db("insert ignore into WA$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (autorizacion anulada)') as descrip from $cade$arch as a $relacion $condicion","");



/* AUTORIZACIONES: no envida) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(d.fS1!='0000-00-00 00:00:00');
		db("insert ignore into WA$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (no enviada al prestador)') as descrip from $cade$arch as a $relacion $condicion","");



/* AUTORIZACIONES:  cantidad autorizada) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(b.cAut > 0);
		db("insert ignore into WA$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (cantidad autorizada 0)') as descrip from $cade$arch as a $relacion $condicion","");



/* AUTORIZACIONES: afiliado errado) */
SELECT * FROM tmp_AC 
LEFT JOIN maestroIdentificaciones as c ON c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(c.numeroCarnet=d.nC );
		db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (autorizacion corresponde a otro afiliado)') as descrip from $cade$arch as a $relacion $condicion","");



/* AUTORIZACIONES: error de servicio) */				   
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(b.codigo=a.codigoConsulta);
		db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (servicio facturado no corresponde a servicio autorizado)') as descrip from $cade$arch as a $relacion $condicion","");




/* AUTORIZACIONES: error de prestador) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !(a.codigoIps=b.pAut);
		db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (servicio no autorizado a su IPS)') as descrip from $cade$arch as a $relacion $condicion","");


				  
/* AUTORIZACIONES: cobrar en otra factura) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd
LEFT JOIN facturas as e on e.id = b.nFac;
WHERE a.numeroAutorizacion != '' and !(b.nFac=0);
		db("insert ignore into IN$arch select a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida (ya asociada a la factura  ',e.numeroFactura,')') as descrip from $cade$arch as a $relacion $condicion","");


				   
/* AUTORIZACIONES: referencia en diferentes facturas en el RIPS) */				   				  
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and b.cAut='1';
		db("insert ignore into IN$arch
			select
				a.nR,'$cade' as tA,concat_ws('-',a.numeroAutorizacion,' Error autorizacion no valida: tiene referencia multiple ') as descrip
			from $cade$arch as a
			$relacion
			$condicion
			GROUP BY a.numeroAutorizacion
			HAVING count(*) > 1
		","");



/*************** AUTORIZACIONES: fecha futura) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and !((d.fecha - interval 1 day) <= a.fechaConsulta );
		

/*************** AUTORIZACIONES: numeroContrato) */
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN AF$arch as e ON e.codigoIps = a.codigoIps and e.numeroFactura=a.numeroFactura
LEFT JOIN maestroRed as f ON f.codigoIps=e.codigoIps and f.numeroContrato=e.numeroContrato;
WHERE a.numeroAutorizacion != '' and !(b.cont = f.id);
		


        // 10.- AUTORIZACIONES: negadas)
SELECT * FROM tmp_AC 
LEFT JOIN at31 as b ON b.id=a.numeroAutorizacion
LEFT JOIN at3 as d ON d.id=b.idd;
WHERE a.numeroAutorizacion != '' and b.cAut = 0;
		


/* AUTORIZACIONES: Autorizacion obligatoria) */        
SELECT * FROM tmp_AC 
LEFT JOIN AF$arch as e ON e.codigoIps = a.codigoIps and e.numeroFactura=a.numeroFactura
LEFT JOIN maestroRed as f ON f.numeroContrato = e.numeroContrato and f.codigoIps = a.codigoIps
LEFT JOIN maestroRedTarifas as g ON g.idCtro = f.id and g.codigo = a.codigoConsulta;
WHERE g.id is not null and g.autObli='1' and a.numeroAutorizacion = '';
		



        /*"codigoConsulta"*/  
SELECT * FROM tmp_AC 
LEFT JOIN refCups as b ON b.codigo=a.codigoConsulta;
WHERE b.descrip is null  or b.AT != 'C';
		


SELECT * FROM tmp_AC 
LEFT JOIN refCups as b ON b.codigo = a.codigoConsulta;
WHERE b.descrip is not null and !(a.fechaConsulta between b.fi and b.ff);
	


SELECT * FROM tmp_AC 
LEFT JOIN refIps as b ON b.codigo=a.codigoIps and b.serHab like '%356%';
WHERE a.codigoConsulta in ('890202','890302') and b.descrip is null;
	



        /*"finalidadConsulta"*/
SELECT * FROM tmp_AC 
LEFT JOIN refFinalidadConsulta as d ON d.codigo=a.finalidadConsulta;
WHERE d.codigo is NULL;
	


SELECT * FROM tmp_AC 
LEFT JOIN refFinalidadConsulta as b ON b.codigo=a.finalidadConsulta
LEFT JOIN US$arch as c on c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE (b.genero!= '' and b.genero!=c.genero);
	


SELECT * FROM tmp_AC 
LEFT JOIN refFinalidadConsulta as b ON b.codigo=a.finalidadConsulta
LEFT JOIN maestroIdentificaciones as e ON e.tipoIdentificacion=a.tipoIdentificacion and e.identificacion=a.identificacion
LEFT JOIN maestroAfiliados as c ON c.numeroCarnet = e.numeroCarnet;
WHERE not (b.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= b.eMax);
	



        /*"causaExterna"*/
SELECT * FROM tmp_AC 
LEFT JOIN refCausaExterna as b ON b.codigo=a.codigoCausaExterna;
WHERE b.descrip is null or a.codigoCausaExterna='';
	



        /*"diagnosticoPrincipal"*/
SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnosticoPrincipal;
WHERE b.descrip is null or a.diagnosticoPrincipal='';
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnosticoPrincipal
LEFT JOIN maestroIdentificaciones as e ON e.tipoIdentificacion=a.tipoIdentificacion and e.identificacion=a.identificacion
LEFT JOIN maestroAfiliados as c ON c.numeroCarnet = e.numeroCarnet;
WHERE not (b.eMin <= greatest(0,$wEdad) and greatest(0,$wEdad) <= b.eMax);
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnosticoPrincipal
LEFT JOIN US$arch as c on c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE (b.genero!= '' and b.genero!=c.genero);
	



        /*"diagnostico1"*/
SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico1;
WHERE b.descrip is null and a.diagnostico1!='';
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico1
LEFT JOIN maestroIdentificaciones as e ON e.tipoIdentificacion=a.tipoIdentificacion and e.identificacion=a.identificacion
LEFT JOIN maestroAfiliados as c ON c.numeroCarnet = e.numeroCarnet;
WHERE not (b.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= b.eMax) and a.diagnostico1!='';
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico1
LEFT JOIN US$arch as c on c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE (b.genero!= '' and b.genero!=c.genero) and a.diagnostico1!='';
	



        /*"diagnostico2"*/
SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico2;
WHERE b.descrip is null and a.diagnostico2!='';
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico2
LEFT JOIN maestroIdentificaciones as e ON e.tipoIdentificacion=a.tipoIdentificacion and e.identificacion=a.identificacion
LEFT JOIN maestroAfiliados as c ON c.numeroCarnet = e.numeroCarnet;
WHERE not (b.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad) <= b.eMax) and a.diagnostico2!='';
	


SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico2
LEFT JOIN US$arch as c on c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE (b.genero!= '' and b.genero!=c.genero) and a.diagnostico2!='';
    



        /*"diagnostico3"*/
SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico3;
WHERE b.descrip is null and a.diagnostico3!='';



SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico3
LEFT JOIN maestroIdentificaciones as e ON e.tipoIdentificacion=a.tipoIdentificacion and e.identificacion=a.identificacion
LEFT JOIN maestroAfiliados as c ON c.numeroCarnet = e.numeroCarnet;
WHERE not (b.eMin <=  greatest(0,$wEdad) and greatest(0,$wEdad)  <= b.eMax) and a.diagnostico3!='';



SELECT * FROM tmp_AC 
LEFT JOIN refCie10 as b ON b.codigo=a.diagnostico3
LEFT JOIN US$arch as c on c.tipoIdentificacion=a.tipoIdentificacion and c.identificacion=a.identificacion;
WHERE (b.genero!= '' and b.genero!=c.genero) and a.diagnostico3!='';







