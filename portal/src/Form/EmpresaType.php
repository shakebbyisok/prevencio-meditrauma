<?php

namespace App\Form;

use App\Entity\ActividadPreventiva;
use App\Entity\Asesoria;
use App\Entity\Cnae;
use App\Entity\CodigoEmpresa;
use App\Entity\Comercial;
use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\EspecialidadPreventiva;
use App\Entity\EstadoEmpresa;
use App\Entity\EstadoPrevencion;
use App\Entity\EstadoSalud;
use App\Entity\EstadoTecnica;
use App\Entity\GrupoActividad;
use App\Entity\GrupoEmpresa;
use App\Entity\ModalidadPreventiva;
use App\Entity\MunicipioSerpa;
use App\Entity\NivelSeguimiento;
use App\Entity\Pais;
use App\Entity\Prescriptor;
use App\Entity\ProvinciaSerpa;
use App\Entity\RegimenSegSocial;
use App\Entity\SectorEmpresarial;
use App\Entity\ServicioPrevencion;
use App\Entity\Tecnico;
use App\Entity\TipoEmpresa;
use App\Entity\Mutua;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo',  TextType::class, ['label' => 'TRANS_CODIGO', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('codigoEmpresa', EntityType::class, ['class' => CodigoEmpresa::class, 'label' => 'TRANS_TIPO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'codigoDescripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('cif', TextType::class, ['label' => 'CIF', 'required' => false])
            ->add('empresa', TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('marcaComercial', TextType::class, ['label' => 'TRANS_MARCA_COMERCIAL', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('grupoEmpresa', EntityType::class, ['class' => GrupoEmpresa::class, 'label' => 'TRANS_GRUPO_EMPRESA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'grupoEmpresa', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('tipoEmpresa', EntityType::class, ['class' => TipoEmpresa::class, 'label' => 'TRANS_TIPO_EMPRESA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('trabajadores', TextType::class, ['label' => 'TRANS_TRABAJADORES', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('agente', EntityType::class, ['class' => Comercial::class, 'label' => 'TRANS_AGENTE', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('vigilanciaSalud', EntityType::class, ['class' => Tecnico::class, 'label' => 'TRANS_VIGILANCIA_SALUD', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('gestorAdministrativo', EntityType::class, ['class' => Tecnico::class, 'label' => 'TRANS_GESTOR_ADM', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('regimen', EntityType::class, ['class' => RegimenSegSocial::class, 'label' => 'TRANS_REGIMEN', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('ccc', TextType::class, ['label' => 'TRANS_CCC', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('colaborador', EntityType::class, ['class' => Asesoria::class, 'label' => 'TRANS_COLABORADOR', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('mutua', EntityType::class, ['class' => Mutua::class, 'label' => 'TRANS_MUTUA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('spa', EntityType::class, ['class' => ServicioPrevencion::class, 'label' => 'TRANS_SPA', 'translation_domain' => 'empresas', 'data' => $options['spa'], 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('prescriptor', EntityType::class, ['class' => Prescriptor::class, 'label' => 'TRANS_PRESCRIPTOR', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('modalidadPreventiva', EntityType::class, ['class' => ModalidadPreventiva::class, 'label' => 'TRANS_MODALIDAD_PREVENTIVA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('centroMedico', TextType::class, ['label' => false, 'required' => false])
	        ->add('consentimientoCesionDatos', CheckboxType::class, ['label' => 'TRANS_CESION_DATOS', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('domicilioPostal', TextType::class, ['label' => 'TRANS_DOMICILIO', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('localidadPostal', TextType::class, ['label' => 'TRANS_LOCALIDAD', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('codigoPostalPostal', TextType::class, ['label' => 'TRANS_CODIGO_POSTAL', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaPostal', TextType::class, ['label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaPostalSerpa', EntityType::class, ['class' => ProvinciaSerpa::class, 'label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('municipioPostalSerpa', EntityType::class, ['class' => MunicipioSerpa::class, 'label' => 'TRANS_MUNICIPIO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('domicilioFiscal', TextType::class, ['label' => 'TRANS_DOMICILIO', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('localidadFiscal', TextType::class, ['label' => 'TRANS_LOCALIDAD', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('codigoPostalFiscal', TextType::class, ['label' => 'TRANS_CODIGO_POSTAL', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaFiscal', TextType::class, ['label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaFiscalSerpa', EntityType::class, ['class' => ProvinciaSerpa::class, 'label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('municipioFiscalSerpa', EntityType::class, ['class' => MunicipioSerpa::class, 'label' => 'TRANS_MUNICIPIO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('paisFiscal', EntityType::class, ['class' => Pais::class, 'label' => 'TRANS_PAIS', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('telefono1', TextType::class, ['label' => 'TRANS_TELEFONO1', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('telefono2', TextType::class, ['label' => 'TRANS_TELEFONO2', 'translation_domain' => 'empresas', 'required' => false])
	        //->add('fax', TextType::class, ['label' => 'TRANS_FAX', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('nombreRepresentante', TextType::class, ['label' => 'TRANS_NOMBRE_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('dniRepresentante', TextType::class, ['label' => 'TRANS_DNI_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('cargoRepresentante', TextType::class, ['label' => 'TRANS_CARGO_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('personaContacto', TextType::class, ['label' => 'TRANS_NOMBRE_CONTACTO', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('estadoAdministrativoPrevencion', EntityType::class, ['class' => EstadoEmpresa::class, 'label' => 'TRANS_ESTADO_ADM_PREV', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('estadoAdministrativoVigilanciaSalud', EntityType::class, ['class' => EstadoEmpresa::class, 'label' => 'TRANS_ESTADO_ADM_VIG', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('estadoAreaAdministracion', EntityType::class, ['class' => EstadoPrevencion::class, 'label' => 'TRANS_ESTADO_AREA_ADM', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('estadoAreaTecnica', EntityType::class, ['class' => EstadoTecnica::class, 'label' => 'TRANS_ESTADO_AREA_TEC', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('estadoAreaVigilanciaSalud', EntityType::class, ['class' => EstadoSalud::class, 'label' => 'TRANS_ESTADO_AREA_VIG', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('fechaAltaTecnica', DateType::class, ['label' => 'TRANS_FECHA_ALTA', 'translation_domain' => 'empresas', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fechaBajaTecnica', DateType::class, ['label' => 'TRANS_FECHA_BAJA', 'translation_domain' => 'empresas', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('motivoBajaTecnica', TextareaType::class, ['label' => 'TRANS_MOTIVO_BAJA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('fechaAltaVigilanciaSalud', DateType::class, ['label' => 'TRANS_FECHA_ALTA', 'translation_domain' => 'empresas', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fechaBajaVigilanciaSalud', DateType::class, ['label' => 'TRANS_FECHA_BAJA', 'translation_domain' => 'empresas', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('motivoBajaVigilanciaSalud', TextareaType::class, ['label' => 'TRANS_MOTIVO_BAJA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('actividad', TextType::class, ['label' => 'TRANS_DESC_ACTIVIDAD', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('sectorEmpresarial', EntityType::class, ['class' => SectorEmpresarial::class, 'label' => 'TRANS_SECTOR_EMP', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('grupoActividad', EntityType::class, ['class' => GrupoActividad::class, 'label' => 'TRANS_GRUPO_ACTIVIDAD', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('actividadPreventiva', EntityType::class, ['class' => ActividadPreventiva::class, 'label' => 'TRANS_ACTIVIDAD_PREV', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('especialidadPreventiva', EntityType::class, ['class' => EspecialidadPreventiva::class, 'label' => 'TRANS_ESP_PREV', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('actividadIncluidaAnexo', CheckboxType::class, ['label' => 'TRANS_ACTIVIDDAD_INCLUIDA_ANEXO', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('empresaEtt', CheckboxType::class, ['label' => 'TRANS_ETT', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('nivelRiesgoSubjetivoInterno', ChoiceType::class, ['choices' => ['TRANS_RIESGO_ALTO' => 1, 'TRANS_RIESGO_MEDIO' => 2, 'TRANS_RIESGO_BAJO' => 3], 'translation_domain' => 'empresas', 'expanded' => true, 'multiple' => false, 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'form-check-input-styled-primary']])
	        ->add('nivelSeguimientoEmpresa', EntityType::class, ['class' => NivelSeguimiento::class, 'label' => false, 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('excluirCalculoRatios', CheckboxType::class, ['label' => 'TRANS_EXCLUIR_RATIOS', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('excluirMemorialAnual', CheckboxType::class, ['label' => 'TRANS_EXCLUIR_MEMORIAL_ANUAL', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('observacionesGenerales', TextareaType::class, ['label' => 'TRANS_OBS_GEN', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('observacionesAreaMedicina', TextareaType::class, ['label' => 'TRANS_OBS_AREA_MED', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('observacionesAreaTecnica', TextareaType::class, ['label' => 'TRANS_OBS_AREA_TEC', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('anulado', CheckboxType::class, ['label' => false, 'required' => false, 'disabled' => true, 'attr' => ['class' => 'form-check-input-switch', 'data-on-color' => 'danger', 'data-off-color' => 'success', 'data-on-text' => 'No activa', 'data-off-text' => 'Activa']])
	        ->add('actuaciones', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('avisosControl', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('estructuraDepartamental', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Empresa::class,
	        'disabled' => null,
            'spa' => null
        ]);
    }
}
