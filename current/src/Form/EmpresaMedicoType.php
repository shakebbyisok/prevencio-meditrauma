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
use App\Entity\EstadoEmpresaSalud;
use App\Entity\EstadoEmpresaTecnico;
use App\Entity\EstadoPrevencion;
use App\Entity\EstadoSalud;
use App\Entity\EstadoTecnica;
use App\Entity\GrupoActividad;
use App\Entity\GrupoEmpresa;
use App\Entity\IdiomaEmpresa;
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
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpresaMedicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anulado', CheckboxType::class, ['label' => false, 'required' => false, 'disabled' => true, 'attr' => ['class' => 'form-check-input-switch', 'data-on-color' => 'danger', 'data-off-color' => 'success', 'data-on-text' => 'No activa', 'data-off-text' => 'Activa']])
    //            ->add('codigoTecnico',  TextType::class, ['label' => 'TRANS_CODIGO', 'translation_domain' => 'empresas', 'required' => false])
    //            fix 24/03/2025 Ticket#2025032210000012 - FALLA CREACION EMPRESAS
            ->add('codigo',  TextType::class, ['label' => 'TRANS_CODIGO', 'translation_domain' => 'empresas', 'required' => false, 'disabled' => true])
            //	        ->add('codigoEmpresa', EntityType::class, ['class' => CodigoEmpresa::class, 'label' => 'TRANS_TIPO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'codigoDescripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('estadoAdministrativoVigilanciaSalud', EntityType::class, ['class' => EstadoPrevencion::class, 'label' => 'TRANS_ESTADO', 'translation_domain' => 'empresas', 'required' => false, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('grupoEmpresa', EntityType::class, ['class' => GrupoEmpresa::class, 'label' => 'TRANS_GRUPO_EMPRESA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'grupoEmpresa', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('cif', TextType::class, ['label' => 'CIF', 'required' => false])
            ->add('empresa', TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('marcaComercial', TextType::class, ['label' => 'TRANS_MARCA_COMERCIAL', 'translation_domain' => 'empresas', 'required' => false])
            ->add('domicilioPostal', TextType::class, ['label' => 'TRANS_DOMICILIO', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('localidadPostal', TextType::class, ['label' => 'TRANS_LOCALIDAD', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('codigoPostalPostal', TextType::class, ['label' => 'TRANS_CODIGO_POSTAL', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaPostal', TextType::class, ['label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('provinciaPostalSerpa', EntityType::class, ['class' => ProvinciaSerpa::class, 'label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('municipioPostalSerpa', EntityType::class, ['class' => MunicipioSerpa::class, 'label' => 'TRANS_MUNICIPIO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('telefono1', TextType::class, ['label' => 'TRANS_TELEFONO1', 'translation_domain' => 'empresas', 'required' => false])
	        ->add('telefono2', TextType::class, ['label' => 'TRANS_TELEFONO2', 'translation_domain' => 'empresas', 'required' => false])
            ->add('agente', EntityType::class, ['class' => Comercial::class, 'label' => 'TRANS_AGENTE', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false);
            }, 'attr' => ['class' => 'select-search']])
            ->add('vigilanciaSalud', EntityType::class, ['class' => Tecnico::class, 'label' => 'TRANS_VIGILANCIA_SALUD', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->andWhere('u.vigilanciaSalud = true')
                    ->setParameter('anulado', false);
            }, 'attr' => ['class' => 'select-search']])
            ->add('gestorAdministrativo', EntityType::class, ['class' => Tecnico::class, 'label' => 'TRANS_GESTOR_ADM', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->andWhere('u.gestorAdministrativo = true')
                    ->setParameter('anulado', false);
            }, 'attr' => ['class' => 'select-search']])
            ->add('colaborador', EntityType::class, ['class' => Asesoria::class, 'label' => 'TRANS_COLABORADOR', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('mutua', EntityType::class, ['class' => Mutua::class, 'label' => 'TRANS_MUTUA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('spa', EntityType::class, ['class' => ServicioPrevencion::class, 'label' => 'TRANS_SPA', 'translation_domain' => 'empresas', 'data' => $options['spa'], 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('prescriptor', EntityType::class, ['class' => Prescriptor::class, 'label' => 'TRANS_PRESCRIPTOR', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('modalidadPreventiva', EntityType::class, ['class' => ModalidadPreventiva::class, 'label' => 'TRANS_MODALIDAD_PREVENTIVA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            //	        ->add('fax', TextType::class, ['label' => 'TRANS_FAX', 'translation_domain' => 'empresas', 'required' => false])
            ->add('idioma', EntityType::class, ['class' => IdiomaEmpresa::class, 'label' => 'Idioma', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
//            ->add('anulado', CheckboxType::class, ['label' => false, 'required' => false, 'disabled' => true, 'attr' => ['class' => 'form-check-input-switch', 'data-on-color' => 'danger', 'data-off-color' => 'success', 'data-on-text' => 'No activa', 'data-off-text' => 'Activa']])
            ->add('observacionesGenerales', TextareaType::class, ['label' => 'TRANS_OBS_GEN', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('observacionesAreaMedicina', TextareaType::class, ['label' => 'TRANS_OBS_AREA_MED', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('observacionesAreaTecnica', TextareaType::class, ['label' => 'TRANS_OBS_AREA_TEC', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('logo',  FileType::class, ['label' => 'TRANS_LOGO', 'translation_domain' => 'empresas', 'required' => false, 'mapped' => false])
            ->add('nombreRepresentante', TextType::class, ['label' => 'TRANS_NOMBRE_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('dniRepresentante', TextType::class, ['label' => 'TRANS_DNI_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('cargoRepresentante', TextType::class, ['label' => 'TRANS_CARGO_RESPONSABLE_EMPRESA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('personaContacto', TextType::class, ['label' => 'TRANS_NOMBRE_CONTACTO', 'translation_domain' => 'empresas', 'required' => false])
            ->add('pruebasComplementarias', TextareaType::class, ['label' => 'TRANS_PRUEBAS_COMPLEMENTARIAS', 'translation_domain' => 'citaciones', 'required' => false, 'attr' => ['class' => 'summernote']])
            ->add('pruebasEspeciales', CheckboxType::class, ['label' => 'TRANS_PRUEBAS_ESPECIALES', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
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
