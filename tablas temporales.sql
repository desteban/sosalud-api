CREATE TABLE IF NOT EXISTS tmp_AC (
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
  PRIMARY KEY (nr)
);

CREATE TABLE IF NOT EXISTS tmp_AD (
  numeroFactura varchar(20) NOT NULL DEFAULT '',
  codigoIps varchar(20) NOT NULL DEFAULT '',
  agrupacionServicios char(2) NOT NULL DEFAULT '',
  cantidad int NOT NULL DEFAULT '0',
  valorUnitario double(15,2) NOT NULL DEFAULT '0.00',
  totalConcepto double(15,2) NOT NULL DEFAULT '0.00',
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);

CREATE TABLE IF NOT EXISTS tmp_AF (
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
);

CREATE TABLE IF NOT EXISTS tmp_AH (
  numeroFactura varchar(20) NOT NULL DEFAULT '',
  codigoIps varchar(20) NOT NULL DEFAULT '',
  tipoIdentificacion char(2) NOT NULL DEFAULT '',
  identificacion varchar(24) NOT NULL DEFAULT '',
  codigoViaIngreso char(1) NOT NULL DEFAULT '',
  fechaIngreso date NOT NULL DEFAULT '0000-01-01',
  horaIngreso time NOT NULL DEFAULT '00:00:00',
  numeroAutorizacion int DEFAULT NULL,
  codigoCausaExterna char(2) NOT NULL DEFAULT '',
  diagnosticoIngreso varchar(4) NOT NULL DEFAULT '',
  diagnosticoEgreso varchar(4) DEFAULT NULL,
  diagnostico1 varchar(4) DEFAULT NULL,
  diagnostico2 varchar(4) DEFAULT NULL,
  diagnostico3 varchar(4) DEFAULT NULL,
  codigoComplicacion varchar(4) DEFAULT NULL,
  estadoSalida enum('1','2') NOT NULL DEFAULT '1',
  causaMuerte varchar(4) DEFAULT NULL,
  fechaEgreso date NOT NULL DEFAULT '0000-01-01',
  horaEgreso time NOT NULL DEFAULT '00:00:00',
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);


CREATE TABLE IF NOT EXISTS tmp_AM (
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
);

CREATE TABLE IF NOT EXISTS tmp_AN (
  numeroFactura varchar(20) NOT NULL DEFAULT '',
  codigoIps varchar(20) NOT NULL DEFAULT '',
  tipoIdentificacion char(2) NOT NULL DEFAULT '',
  identificacion varchar(20) NOT NULL DEFAULT '',
  fechaNacimiento date NOT NULL DEFAULT '0000-01-01',
  horaNacimiento time NOT NULL DEFAULT '00:00:00',
  edadGestacion tinyint NOT NULL DEFAULT '0',
  controlPrenatal enum('1','2') NOT NULL DEFAULT '1',
  genero enum('M','F') NOT NULL DEFAULT 'M',
  peso int NOT NULL DEFAULT '0',
  diagnostico varchar(4) NOT NULL DEFAULT '',
  diagnosticoMuerte varchar(4) DEFAULT NULL,
  fechaMuerte date DEFAULT NULL,
  horaMuerte time DEFAULT NULL,
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);

CREATE TABLE IF NOT EXISTS tmp_AP (
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
);

CREATE TABLE IF NOT EXISTS tmp_AT (
  numeroFactura varchar(20) NOT NULL DEFAULT '',
  codigoIps varchar(20) NOT NULL DEFAULT '',
  tipoIdentificacion char(2) NOT NULL DEFAULT '',
  identificacion varchar(20) NOT NULL DEFAULT '',
  numeroAutorizacion int NOT NULL,
  tipoServicio tinyint(1) NOT NULL DEFAULT '0',
  codigoServicio varchar(20) NOT NULL DEFAULT '',
  nombreServicio varchar(60) NOT NULL DEFAULT '',
  cantidad varchar(5) NOT NULL DEFAULT '',
  valorUnitario double(15,2) NOT NULL,
  valorTotal double(15,2) NOT NULL,
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);


CREATE TABLE IF NOT EXISTS tmp_AU (
  numeroFactura varchar(20) NOT NULL DEFAULT '',
  codigoIps varchar(20) NOT NULL DEFAULT '',
  tipoIdentificacion char(2) NOT NULL DEFAULT '',
  identificacion varchar(20) NOT NULL DEFAULT '',
  fechaIngreso date NOT NULL DEFAULT '0000-01-01',
  horaIngreso time NOT NULL DEFAULT '00:00:00',
  numeroAutorizacion varchar(15) NOT NULL DEFAULT '',
  causaExterna char(2) NOT NULL DEFAULT '',
  diagnostico varchar(4) NOT NULL DEFAULT '',
  diagnostico1 varchar(4) DEFAULT NULL,
  diagnostico2 varchar(4) DEFAULT NULL,
  diagnostico3 varchar(4) DEFAULT NULL,
  referencia tinyint(1) NOT NULL DEFAULT '0',
  estadoSalida enum('1','2') NOT NULL DEFAULT '1',
  causaMuerte varchar(4) DEFAULT NULL,
  fechaSalida date DEFAULT NULL,
  horaSalida time DEFAULT NULL,
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);


CREATE TABLE IF NOT EXISTS tmp_CT (
  codigoIps varchar(20) NOT NULL DEFAULT '',
  fechaRemision date NOT NULL DEFAULT '0000-01-01',
  codigoArchivo varchar(8) NOT NULL DEFAULT '',
  totalRegistros int NOT NULL DEFAULT '0',
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (nr)
);


CREATE TABLE IF NOT EXISTS tmp_US (
  tipoIdentificacion char(2) NOT NULL DEFAULT '',
  identificacion varchar(20) NOT NULL DEFAULT '',
  codigoEapb varchar(6) NOT NULL DEFAULT '',
  tipoUsuario tinyint(1) NOT NULL DEFAULT '2',
  primerApellido varchar(30) NOT NULL DEFAULT '',
  segundoApellido varchar(30) DEFAULT NULL,
  primerNombre varchar(20) NOT NULL DEFAULT '',
  segundoNombre varchar(20) DEFAULT NULL,
  edad tinyint DEFAULT NULL,
  medidaEdad enum('1','2','3') DEFAULT NULL,
  genero enum('M','F') DEFAULT NULL,
  codigoDepartamento char(2) NOT NULL DEFAULT '',
  codigoMunicipio char(3) NOT NULL DEFAULT '',
  zona enum('U','R') NOT NULL DEFAULT 'U',
  nr integer  NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(nr)
);

DELIMITER $$
    CREATE PROCEDURE IF NOT EXISTS limpiarTablasTemporales()
    BEGIN
        -- set table schema and pattern matching for tables
      SET @schema = 'cta_medica';
      SET @pattern = 'tmp%';

        -- build dynamic sql (DROP TABLE tbl1, tbl2...;)
        SELECT CONCAT('DROP TABLE IF EXISTS ',GROUP_CONCAT(CONCAT(@schema,'.',table_name)),';')
        INTO @droplike
        FROM information_schema.tables
        WHERE @schema = database()
        AND table_name LIKE @pattern;

        -- execute dynamic sql
        PREPARE stmt FROM @droplike;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END $$
DELIMITER

CREATE TABLE IF NOT EXISTS usuarios(
id INTEGER AUTO_INCREMENT PRIMARY KEY,
name varchar(50) UNIQUE NOT NULL,
email varchar(50) UNIQUE NOT NULL,
nombreUsuario varchar(50) NOT NULL,
email_verified_at DATETIME,
password varchar(255) NOT NULL,
remember_token varchar(255),
created_at timestamp,
updated_at timestamp
);

CREATE TABLE IF NOT EXISTS personal_access_tokens(
    id INT NOT NULL AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    token VARCHAR(255) not null,
    expire TIMESTAMP NOT NULL,
    creado TIMESTAMP not NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    PRIMARY KEY (id, usuario_id)
);

CREATE TABLE IF NOT EXISTS tmp_logs_error_$nombreCarpeta(
  contenido TEXT NOT NULL,
  tipo VARCHAR(2) NOT NULL COMMENT 'El tipo de RIPS'
);