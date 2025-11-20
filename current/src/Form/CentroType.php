<?php

namespace App\Form;

use App\Entity\Centro;
use App\Entity\Cnae;
use App\Entity\Empresa;
use App\Entity\EstadoTecnica;
use App\Entity\MunicipioSerpa;
use App\Entity\ProvinciaSerpa;
use App\Entity\RegimenSegSocial;
use App\Entity\TipoCentro;
use Doctrine\Common\Collections\Selectable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CentroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('trabajadores', TextType::class, ['label' => 'TRANS_TRABAJADORES', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('tipo', EntityType::class, ['class' => TipoCentro::class, 'label' => 'TRANS_TIPO', 'translation_domain' => 'centrostrabajo', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('direccion', TextType::class, ['label' => 'TRANS_DIRECCION', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('localidad', TextType::class, ['label' => 'TRANS_LOCALIDAD', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('codigoPostal', TextType::class, ['label' => 'TRANS_CODIGO_POSTAL', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('provincia', TextType::class, ['label' => 'TRANS_PROVINCIA', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('zona', TextType::class, ['label' => 'TRANS_ZONA', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('centroMismaProvincia', CheckboxType::class, ['label' => 'TRANS_CENTRO_MISMA_PROVINCIA', 'translation_domain' => 'centrostrabajo', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('email', EmailType::class, ['label' => 'TRANS_EMAIL', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('telefono', TextType::class, ['label' => 'TRANS_TELEFONO', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('telefono2', TextType::class, ['label' => 'TRANS_TELEFONO2', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('fax', TextType::class, ['label' => 'TRANS_FAX', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('provinciaSerpa', EntityType::class, ['class' => ProvinciaSerpa::class, 'label' => 'TRANS_PROVINCIA', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('municipioSerpa', EntityType::class, ['class' => MunicipioSerpa::class, 'label' => 'TRANS_MUNICIPIO', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('actividadCentro', TextType::class, ['label' => 'TRANS_DESCRIPCION_ACT', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('cnae', EntityType::class, ['class' => Cnae::class, 'label' => 'TRANS_CNAE', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('excluirRatios', CheckboxType::class, ['label' => 'TRANS_EXCLUIR_RATIO', 'translation_domain' => 'centrostrabajo', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('excluirMemoria', CheckboxType::class, ['label' => 'TRANS_EXCLUIR_MEMORIAL_ANUAL', 'translation_domain' => 'centrostrabajo', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('regimenss', EntityType::class, ['class' => RegimenSegSocial::class, 'label' => 'TRANS_REGIMEN', 'translation_domain' => 'empresas', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('ccc', TextType::class, ['label' => 'TRANS_CCC', 'translation_domain' => 'centrostrabajo', 'required' => false])
	        ->add('centroMedico', TextType::class, ['label' => false, 'required' => false])
	        ->add('cesionDatos', CheckboxType::class, ['label' => 'TRANS_CESION_DATOS', 'translation_domain' => 'centrostrabajo', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        //->add('empresa', EntityType::class, ['class' => Empresa::class, 'choice_label' => 'Empresa', 'placeholder' => 'Selecciona una empresa', 'empty_data' => null])
	        ->add('actuaciones', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('avisosControl', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('estructuraCentro', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Centro::class,
        ]);
    }
}
