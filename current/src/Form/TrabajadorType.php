<?php

namespace App\Form;

use App\Entity\PuestoTrabajoGenerico;
use App\Entity\Trabajador;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrabajadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',  TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('dni', TextType::class, ['label' => 'TRANS_DNI', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('sexo', ChoiceType::class, ['choices' => ['TRANS_HOMBRE' => 1, 'TRANS_MUJER' => 2], 'translation_domain' => 'trabajadores', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
	        ->add('fechaNacimiento', DateType::class, ['label' => 'TRANS_DTNAC', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true ])
	        ->add('edad', TextType::class, ['label' => 'TRANS_EDAD', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('grupoEdad', TextType::class, ['label' => 'TRANS_GRUPO_EDAD', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('naf', TextType::class, ['label' => 'TRANS_N_A_SS', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('identificacion', TextType::class, ['label' => 'TRANS_IDENTIFICACION', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('nacionalidad', TextType::class, ['label' => 'TRANS_NACIONALIDAD', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('discapacidad', CheckboxType::class, ['label' => 'TRANS_DISCAPACIDAD', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('puestoTrabajo', EntityType::class, ['class' => PuestoTrabajoGenerico::class, 'choice_label' => 'descripcion', 'label' => 'TRANS_PUESTO_TRABAJO', 'translation_domain' => 'trabajadores', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
	        ->add('cno', TextType::class, ['label' => 'TRANS_CNO', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('categoriaProfesional', TextType::class, ['label' => 'TRANS_CATEGORIA_PROF', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('regimenCotizacionSS', TextType::class, ['label' => 'TRANS_REGIMEN_SS', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('situacionProfesional', TextType::class, ['label' => 'TRANS_SITUACION_PROF', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('autonomo', CheckboxType::class, ['label' => 'TRANS_AUTOMONO', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('ett', CheckboxType::class, ['label' => 'TRANS_ETT', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('subvencionado', CheckboxType::class, ['label' => 'TRANS_SUBVENCIONADO', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('exclusion', CheckboxType::class, ['label' => 'TRANS_EXCLUIR_RATIO', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('avisosControl', TextareaType::class, ['label' => false, 'required' => false])
            ->add('mail', TextType::class, ['label' => 'TRANS_MAIL', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('telefono1', TextType::class, ['label' => 'TRANS_TELEFONO', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trabajador::class,
        ]);
    }
}
