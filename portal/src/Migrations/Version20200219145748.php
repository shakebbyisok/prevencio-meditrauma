<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219145748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE facturacion_lineas_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE especialidadpreventiva_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estadoempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estadoprevencion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estadosalud_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE actividadpreventiva_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estadotecnica_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fasecontrato_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cnaeempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contratopago_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE formapago_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE grupoactividad_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE correoempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE grupoempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE listaservicioscontratados_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE modalidadpreventiva_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE municipioserpa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE nivelseguimiento_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pagopendiente_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE privilegioroles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE provinciaserpa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE regimensegsocial_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sectorempresarial_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE serviciocontratado_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE servicioprevencion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tarifacontrato_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tecnicoempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tipocentro_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tipocontrato_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tipoempresa_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tiposerviciocontratado_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE entidad_bancaria_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE entidad_bancaria (id INT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, nrb INT DEFAULT NULL, bic VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE import_empresas');
        $this->addSql('DROP TABLE import_pais');
        $this->addSql('DROP TABLE import_contratos');
        $this->addSql('DROP TABLE import_tipo_contrato');
        $this->addSql('DROP TABLE import_cnae_empresa');
        $this->addSql('DROP TABLE import_comercial');
        $this->addSql('DROP TABLE import_modalidad_preventiva');
        $this->addSql('DROP TABLE import_tipo_empresa');
        $this->addSql('DROP TABLE import_centro_trabajo');
        $this->addSql('DROP TABLE import_especialidad_preventiva');
        $this->addSql('DROP TABLE import_trabajadores');
        $this->addSql('DROP TABLE import_asesorias');
        $this->addSql('DROP TABLE import_estado_empresa');
        $this->addSql('DROP TABLE import_cnae');
        $this->addSql('DROP TABLE import_estado_prevencion');
        $this->addSql('DROP TABLE import_estado_salud');
        $this->addSql('DROP TABLE import_estado_tecnica');
        $this->addSql('DROP TABLE import_forma_pago');
        $this->addSql('DROP TABLE import_tecnicos');
        $this->addSql('DROP TABLE import_grupo_actividad');
        $this->addSql('DROP TABLE import_grupo_empresarial');
        $this->addSql('DROP TABLE import_municipio_serpa');
        $this->addSql('DROP TABLE import_mutuas');
        $this->addSql('DROP TABLE import_prescriptor');
        $this->addSql('DROP TABLE import_provincia_serpa');
        $this->addSql('DROP TABLE import_servicio_prevencion');
        $this->addSql('DROP TABLE import_sector_empresarial');
        $this->addSql('DROP TABLE import_contrato_pagos');
        $this->addSql('DROP TABLE import_pagos_facturados');
        $this->addSql('DROP TABLE import_factura_lineas');
        $this->addSql('DROP TABLE import_serie_factura');
        $this->addSql('DROP TABLE import_facturas');
        $this->addSql('DROP TABLE import_factura_comisiones');
        $this->addSql('DROP TABLE import_conceptos');
        $this->addSql('DROP TABLE import_factura_lineas_pagos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE entidad_bancaria_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE facturacion_lineas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE especialidadpreventiva_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estadoempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estadoprevencion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estadosalud_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE actividadpreventiva_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estadotecnica_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fasecontrato_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cnaeempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contratopago_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE formapago_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE grupoactividad_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE correoempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE grupoempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE listaservicioscontratados_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE modalidadpreventiva_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE municipioserpa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE nivelseguimiento_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pagopendiente_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE privilegioroles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE provinciaserpa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE regimensegsocial_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sectorempresarial_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE serviciocontratado_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE servicioprevencion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tarifacontrato_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tecnicoempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tipocentro_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tipocontrato_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tipoempresa_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tiposerviciocontratado_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE import_empresas (id VARCHAR(2000) DEFAULT NULL, scodcliente VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL, snombrecomercial VARCHAR(2000) DEFAULT NULL, nestado VARCHAR(2000) DEFAULT NULL, sss VARCHAR(2000) DEFAULT NULL, sdireccion VARCHAR(2000) DEFAULT NULL, slocalidad VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, scif VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL, smail VARCHAR(2000) DEFAULT NULL, spersonacontacto VARCHAR(2000) DEFAULT NULL, srepresentante VARCHAR(2000) DEFAULT NULL, sdni_representante VARCHAR(2000) DEFAULT NULL, scargo_representante VARCHAR(2000) DEFAULT NULL, sdescactividad VARCHAR(2000) DEFAULT NULL, ngrupoactividad VARCHAR(2000) DEFAULT NULL, nactividadpreventiva VARCHAR(2000) DEFAULT NULL, sespecialidadpreventiva VARCHAR(2000) DEFAULT NULL, nmutua VARCHAR(2000) DEFAULT NULL, nasesoria VARCHAR(2000) DEFAULT NULL, ntecnicoresponsable VARCHAR(2000) DEFAULT NULL, nnumtrabajadores VARCHAR(2000) DEFAULT NULL, dcancelacion VARCHAR(2000) DEFAULT NULL, smotivocancelacion VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, nservicioprevencion VARCHAR(2000) DEFAULT NULL, bvigilancia VARCHAR(2000) DEFAULT NULL, daltaprevencion VARCHAR(2000) DEFAULT NULL, daltasalud VARCHAR(2000) DEFAULT NULL, dultimaprevencion VARCHAR(2000) DEFAULT NULL, dultimasalud VARCHAR(2000) DEFAULT NULL, bbaja VARCHAR(2000) DEFAULT NULL, dbaja VARCHAR(2000) DEFAULT NULL, smotivobaja VARCHAR(2000) DEFAULT NULL, banexo1 VARCHAR(2000) DEFAULT NULL, bbajasalud VARCHAR(2000) DEFAULT NULL, dbajasalud VARCHAR(2000) DEFAULT NULL, smotivobajasalud VARCHAR(2000) DEFAULT NULL, nestadosalud VARCHAR(2000) DEFAULT NULL, nestadoenprevencion VARCHAR(2000) DEFAULT NULL, nestadoentecnica VARCHAR(2000) DEFAULT NULL, nestadoensalud VARCHAR(2000) DEFAULT NULL, nsectoremp VARCHAR(2000) DEFAULT NULL, nnivelriesgo VARCHAR(2000) DEFAULT NULL, ncomercial VARCHAR(2000) DEFAULT NULL, nformapagohabitual VARCHAR(2000) DEFAULT NULL, ixdelegacion VARCHAR(2000) DEFAULT NULL, sobservacionesmedicina VARCHAR(2000) DEFAULT NULL, sobservacionestecnica VARCHAR(2000) DEFAULT NULL, btecnica VARCHAR(2000) DEFAULT NULL, nresponsableenvigilancia VARCHAR(2000) DEFAULT NULL, ndiapago VARCHAR(2000) DEFAULT NULL, bobras VARCHAR(2000) DEFAULT NULL, bexcluirdememoriaanual VARCHAR(2000) DEFAULT NULL, bexcluirderatios VARCHAR(2000) DEFAULT NULL, ixregimenss VARCHAR(2000) DEFAULT NULL, ixprovincia_social VARCHAR(2000) DEFAULT NULL, ixmunicipio_social VARCHAR(2000) DEFAULT NULL, ixprovincia_fiscal VARCHAR(2000) DEFAULT NULL, ixmunicipio_fiscal VARCHAR(2000) DEFAULT NULL, ixgestoradministrativo VARCHAR(2000) DEFAULT NULL, ixnivelseguimiento VARCHAR(2000) DEFAULT NULL, ixgrupoempresarial VARCHAR(2000) DEFAULT NULL, ixtipoempresa VARCHAR(2000) DEFAULT NULL, ixcentromedico VARCHAR(2000) DEFAULT NULL, bcesiondatos_requierefirma VARCHAR(2000) DEFAULT NULL, scesiondatos_documento VARCHAR(2000) DEFAULT NULL, ixprescriptor VARCHAR(2000) DEFAULT NULL, ixmodalidadpreventiva VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_pais (id VARCHAR(2000) DEFAULT NULL, spais VARCHAR(2000) DEFAULT NULL, scodpais VARCHAR(2000) DEFAULT NULL, nnumdigitosiban VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_contratos (id VARCHAR(2000) DEFAULT NULL, ncodigo VARCHAR(2000) DEFAULT NULL, "nañocontrato" VARCHAR(2000) DEFAULT NULL, nnumcontrato VARCHAR(2000) DEFAULT NULL, scodcontrato VARCHAR(2000) DEFAULT NULL, dcontrato VARCHAR(2000) DEFAULT NULL, sfirmante VARCHAR(2000) DEFAULT NULL, sdnifirmante VARCHAR(2000) DEFAULT NULL, sencalidadde VARCHAR(2000) DEFAULT NULL, dentregatecnico VARCHAR(2000) DEFAULT NULL, dentregacliente VARCHAR(2000) DEFAULT NULL, snumcta VARCHAR(2000) DEFAULT NULL, sformapago VARCHAR(2000) DEFAULT NULL, ntipocontrato VARCHAR(2000) DEFAULT NULL, npresupuesto VARCHAR(2000) DEFAULT NULL, "nañopresupuesto" VARCHAR(2000) DEFAULT NULL, bcancelado VARCHAR(2000) DEFAULT NULL, brenovado VARCHAR(2000) DEFAULT NULL, nimporterenovacion VARCHAR(2000) DEFAULT NULL, bporservicio VARCHAR(2000) DEFAULT NULL, nnumtrabajadoressalud VARCHAR(2000) DEFAULT NULL, nnumtrabajadoresprevencion VARCHAR(2000) DEFAULT NULL, ixcontratotipo VARCHAR(2000) DEFAULT NULL, ixcontratomodalidad VARCHAR(2000) DEFAULT NULL, sreferencia VARCHAR(2000) DEFAULT NULL, ixfase VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, benhistorico VARCHAR(2000) DEFAULT NULL, nnumtrabajadoresotros VARCHAR(2000) DEFAULT NULL, bpendienterevision VARCHAR(2000) DEFAULT NULL, smotivorevision VARCHAR(2000) DEFAULT NULL, nrenovacion_duracion VARCHAR(2000) DEFAULT NULL, nrenovacion_numpagos VARCHAR(2000) DEFAULT NULL, brenovacion_dias VARCHAR(2000) DEFAULT NULL, nrenovacion_cadencia VARCHAR(2000) DEFAULT NULL, brenovacion_primerpagocerodias VARCHAR(2000) DEFAULT NULL, nvisitasconcertadas VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_tipo_contrato (id VARCHAR(2000) DEFAULT NULL, scontratotipo VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_cnae_empresa ("﻿nEmpresa" VARCHAR(255) DEFAULT NULL, ncnae VARCHAR(255) DEFAULT NULL, bprincipal VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_comercial (id VARCHAR(2000) DEFAULT NULL, scomercial VARCHAR(2000) DEFAULT NULL, skey VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_modalidad_preventiva (id VARCHAR(2000) DEFAULT NULL, smodalidad VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_tipo_empresa (id VARCHAR(2000) DEFAULT NULL, sempresatipo VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bdesactivado VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_centro_trabajo (id VARCHAR(2000) DEFAULT NULL, ncodigo VARCHAR(2000) DEFAULT NULL, snombrecentro VARCHAR(2000) DEFAULT NULL, sdireccion VARCHAR(2000) DEFAULT NULL, slocalidad VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL, smail VARCHAR(2000) DEFAULT NULL, nnumtrabajadores VARCHAR(2000) DEFAULT NULL, sactividad VARCHAR(2000) DEFAULT NULL, bcancelado VARCHAR(2000) DEFAULT NULL, spersonacontacto VARCHAR(2000) DEFAULT NULL, sss VARCHAR(2000) DEFAULT NULL, scodcentro VARCHAR(2000) DEFAULT NULL, ixcnae VARCHAR(2000) DEFAULT NULL, ixtipocentro VARCHAR(2000) DEFAULT NULL, bprovincianolimitrofe VARCHAR(2000) DEFAULT NULL, szona VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, bexcluirdememoriaanual VARCHAR(2000) DEFAULT NULL, bexcluirderatios VARCHAR(2000) DEFAULT NULL, ixregimenss VARCHAR(2000) DEFAULT NULL, ixprovincia VARCHAR(2000) DEFAULT NULL, ixmunicipio VARCHAR(2000) DEFAULT NULL, ixcentromedico VARCHAR(2000) DEFAULT NULL, bcesiondatos_requierefirma VARCHAR(2000) DEFAULT NULL, scesiondatos_documento VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_especialidad_preventiva (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_trabajadores (id VARCHAR(2000) DEFAULT NULL, ncodigo VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL, sdni VARCHAR(2000) DEFAULT NULL, npuesto VARCHAR(2000) DEFAULT NULL, scategoria VARCHAR(2000) DEFAULT NULL, sdomicilio VARCHAR(2000) DEFAULT NULL, spoblacion VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, spais VARCHAR(2000) DEFAULT NULL, dnacimiento VARCHAR(2000) DEFAULT NULL, nsexo VARCHAR(2000) DEFAULT NULL, bbaja VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL, smail VARCHAR(2000) DEFAULT NULL, sss VARCHAR(2000) DEFAULT NULL, sidentificador VARCHAR(2000) DEFAULT NULL, bminusvalia VARCHAR(2000) DEFAULT NULL, besautonomo VARCHAR(2000) DEFAULT NULL, besdeett VARCHAR(2000) DEFAULT NULL, bessubcontratado VARCHAR(2000) DEFAULT NULL, ixregimenss VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, bexcluirdememoriaanual VARCHAR(2000) DEFAULT NULL, bexcluirderatios VARCHAR(2000) DEFAULT NULL, sfoto VARCHAR(2000) DEFAULT NULL, ixpais VARCHAR(2000) DEFAULT NULL, ixprovincia VARCHAR(2000) DEFAULT NULL, ixmunicipio VARCHAR(2000) DEFAULT NULL, ixsituacionlaboral VARCHAR(2000) DEFAULT NULL, strabajador_nombre VARCHAR(2000) DEFAULT NULL, strabajador_apellido1 VARCHAR(2000) DEFAULT NULL, strabajador_apellido2 VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_asesorias (id VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL, scontacto VARCHAR(2000) DEFAULT NULL, sdireccion VARCHAR(2000) DEFAULT NULL, slocalidad VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, scif VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL, nporccomision VARCHAR(2000) DEFAULT NULL, smail VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, bdesactivada VARCHAR(2000) DEFAULT NULL, ixusuarioresponsable VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_estado_empresa (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bprecliente VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_cnae (id VARCHAR(2000) DEFAULT NULL, npadre VARCHAR(2000) DEFAULT NULL, scnae VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_estado_prevencion (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bprecliente VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_estado_salud (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bprecliente VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_estado_tecnica (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_forma_pago (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, ixformapagocontable VARCHAR(2000) DEFAULT NULL, norden VARCHAR(2000) DEFAULT NULL, bdesactivada VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_tecnicos (id VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL, salias VARCHAR(2000) DEFAULT NULL, stitulacion VARCHAR(2000) DEFAULT NULL, sespecialidad VARCHAR(2000) DEFAULT NULL, sdni VARCHAR(2000) DEFAULT NULL, nporccomision VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_grupo_actividad (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_grupo_empresarial (id VARCHAR(2000) DEFAULT NULL, sgrupoempresarial VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bdesactivado VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_municipio_serpa (id VARCHAR(2000) DEFAULT NULL, ixprovincia VARCHAR(2000) DEFAULT NULL, scodmunicipio VARCHAR(2000) DEFAULT NULL, smunicipio VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_mutuas (id VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL, scontacto VARCHAR(2000) DEFAULT NULL, sdireccion VARCHAR(2000) DEFAULT NULL, slocalidad VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, scif VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_prescriptor (id VARCHAR(2000) DEFAULT NULL, sprescriptor VARCHAR(2000) DEFAULT NULL, scontacto VARCHAR(2000) DEFAULT NULL, sdireccion VARCHAR(2000) DEFAULT NULL, slocalidad VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL, scodpostal VARCHAR(2000) DEFAULT NULL, scif VARCHAR(2000) DEFAULT NULL, stelefono1 VARCHAR(2000) DEFAULT NULL, stelefono2 VARCHAR(2000) DEFAULT NULL, sfax VARCHAR(2000) DEFAULT NULL, smail VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, bdesactivado VARCHAR(2000) DEFAULT NULL, ixusuarioresponsable VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_provincia_serpa (id VARCHAR(2000) DEFAULT NULL, scodprovincia VARCHAR(2000) DEFAULT NULL, sprovincia VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_servicio_prevencion (id VARCHAR(2000) DEFAULT NULL, snombre VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_sector_empresarial (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_contrato_pagos (id VARCHAR(2000) DEFAULT NULL, ncontrato VARCHAR(2000) DEFAULT NULL, "nañocontrato" VARCHAR(2000) DEFAULT NULL, npago VARCHAR(2000) DEFAULT NULL, nporcentajepago VARCHAR(2000) DEFAULT NULL, nmesesvencimiento VARCHAR(2000) DEFAULT NULL, dvencimiento VARCHAR(2000) DEFAULT NULL, nimportepagosiniva VARCHAR(2000) DEFAULT NULL, nimportepagosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagonosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagototal VARCHAR(2000) DEFAULT NULL, niva VARCHAR(2000) DEFAULT NULL, bcomisionablecolaborador VARCHAR(2000) DEFAULT NULL, ncomisioncolaborador VARCHAR(2000) DEFAULT NULL, bcomisionabletecnico VARCHAR(2000) DEFAULT NULL, ncomisiontecnico VARCHAR(2000) DEFAULT NULL, bcomisionablecomercial VARCHAR(2000) DEFAULT NULL, ncomisioncomercial VARCHAR(2000) DEFAULT NULL, stextopago VARCHAR(2000) DEFAULT NULL, ixapartado VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_pagos_facturados (id VARCHAR(2000) DEFAULT NULL, npago VARCHAR(2000) DEFAULT NULL, sconceptopago VARCHAR(2000) DEFAULT NULL, nporcentajepago VARCHAR(2000) DEFAULT NULL, nmesesvencimiento VARCHAR(2000) DEFAULT NULL, dvencimiento VARCHAR(2000) DEFAULT NULL, bfacturado VARCHAR(2000) DEFAULT NULL, nimportepagosiniva VARCHAR(2000) DEFAULT NULL, nimportepagosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagonosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagototal VARCHAR(2000) DEFAULT NULL, niva VARCHAR(2000) DEFAULT NULL, ncontratoasociado VARCHAR(2000) DEFAULT NULL, "nañocontratoasociado" VARCHAR(2000) DEFAULT NULL, npanualasociado VARCHAR(2000) DEFAULT NULL, npagoasociado VARCHAR(2000) DEFAULT NULL, bcomisionablecolaborador VARCHAR(2000) DEFAULT NULL, ncomisioncolaborador VARCHAR(2000) DEFAULT NULL, bcomisionabletecnico VARCHAR(2000) DEFAULT NULL, ncomisiontecnico VARCHAR(2000) DEFAULT NULL, bcomisionablecomercial VARCHAR(2000) DEFAULT NULL, ncomisioncomercial VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_factura_lineas (id VARCHAR(2000) DEFAULT NULL, nfactura VARCHAR(2000) DEFAULT NULL, "nañofactura" VARCHAR(2000) DEFAULT NULL, nserie VARCHAR(2000) DEFAULT NULL, ncodigo VARCHAR(2000) DEFAULT NULL, sconcepto VARCHAR(2000) DEFAULT NULL, nunidades VARCHAR(2000) DEFAULT NULL, nimporteunidad VARCHAR(2000) DEFAULT NULL, biva VARCHAR(2000) DEFAULT NULL, nporciva VARCHAR(2000) DEFAULT NULL, niva VARCHAR(2000) DEFAULT NULL, nimporte VARCHAR(2000) DEFAULT NULL, bmanual VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_serie_factura (id VARCHAR(2000) DEFAULT NULL, sserie VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, bdesactivada VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_facturas (id VARCHAR(2000) DEFAULT NULL, ncodigo VARCHAR(2000) DEFAULT NULL, "nañofactura" VARCHAR(2000) DEFAULT NULL, nserie VARCHAR(2000) DEFAULT NULL, nnumfactura VARCHAR(2000) DEFAULT NULL, snumfactura VARCHAR(2000) DEFAULT NULL, dfactura VARCHAR(2000) DEFAULT NULL, nformapago VARCHAR(2000) DEFAULT NULL, bcancelada VARCHAR(2000) DEFAULT NULL, bpagada VARCHAR(2000) DEFAULT NULL, brenovacion VARCHAR(2000) DEFAULT NULL, skeycontrato VARCHAR(2000) DEFAULT NULL, bcomisionable VARCHAR(2000) DEFAULT NULL, bcomisionabletecnico VARCHAR(2000) DEFAULT NULL, bpagadatecnico VARCHAR(2000) DEFAULT NULL, sobservaciones VARCHAR(2000) DEFAULT NULL, bpagadacomercial VARCHAR(2000) DEFAULT NULL, skeyrenovacion VARCHAR(2000) DEFAULT NULL, bexportadocontabilidad VARCHAR(2000) DEFAULT NULL, sidentificador VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_factura_comisiones (id VARCHAR(2000) DEFAULT NULL, nfactura VARCHAR(2000) DEFAULT NULL, bcomisionablecolaborador VARCHAR(2000) DEFAULT NULL, ncomisioncolaborador VARCHAR(2000) DEFAULT NULL, bcomisionabletecnico VARCHAR(2000) DEFAULT NULL, ncomisiontecnico VARCHAR(2000) DEFAULT NULL, bcomisionablecomercial VARCHAR(2000) DEFAULT NULL, ncomisioncomercial VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_conceptos (id VARCHAR(2000) DEFAULT NULL, sdescripcion VARCHAR(2000) DEFAULT NULL, npreciounidad VARCHAR(2000) DEFAULT NULL, nporciva VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('CREATE TABLE import_factura_lineas_pagos (id VARCHAR(2000) DEFAULT NULL, nfactura VARCHAR(2000) DEFAULT NULL, npago VARCHAR(2000) DEFAULT NULL, "nañofactura" VARCHAR(2000) DEFAULT NULL, nserie VARCHAR(2000) DEFAULT NULL, sconcepto VARCHAR(2000) DEFAULT NULL, nporcentajepago VARCHAR(2000) DEFAULT NULL, nmesesvencimiento VARCHAR(2000) DEFAULT NULL, dvencimiento VARCHAR(2000) DEFAULT NULL, bfacturado VARCHAR(2000) DEFAULT NULL, nimportepagosiniva VARCHAR(2000) DEFAULT NULL, nimportepagosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagonosuplidos VARCHAR(2000) DEFAULT NULL, nimportepagototal VARCHAR(2000) DEFAULT NULL, niva VARCHAR(2000) DEFAULT NULL, bpagado VARCHAR(2000) DEFAULT NULL, skeycontrato VARCHAR(2000) DEFAULT NULL, skeypago VARCHAR(2000) DEFAULT NULL, bcomisionablecolaborador VARCHAR(2000) DEFAULT NULL, ncomisioncolaborador VARCHAR(2000) DEFAULT NULL, bcomisionabletecnico VARCHAR(2000) DEFAULT NULL, ncomisiontecnico VARCHAR(2000) DEFAULT NULL, bcomisionablecomercial VARCHAR(2000) DEFAULT NULL, ncomisioncomercial VARCHAR(2000) DEFAULT NULL, nunidades VARCHAR(2000) DEFAULT NULL, nconceptomanual VARCHAR(2000) DEFAULT NULL)');
        $this->addSql('DROP TABLE entidad_bancaria');
    }
}
