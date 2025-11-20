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

class TrabajadorMedicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',  TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('dni', TextType::class, ['label' => 'TRANS_DNI', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('sexo', ChoiceType::class, ['choices' => ['TRANS_HOMBRE' => 1, 'TRANS_MUJER' => 2], 'translation_domain' => 'trabajadores', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
	        ->add('fechaNacimiento', DateType::class, ['label' => 'TRANS_DTNAC', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true ])
	        ->add('naf', TextType::class, ['label' => 'TRANS_N_A_SS', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('discapacidad', CheckboxType::class, ['label' => 'TRANS_DISCAPACIDAD', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('domicilio', TextType::class, ['label' => 'TRANS_DOMICILIO', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('telefono1', TextType::class, ['label' => 'TRANS_TELEFONO', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('mail', TextType::class, ['label' => 'TRANS_MAIL', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('tipoFormacion', TextType::class, ['label' => 'TRANS_TIPO_FORMACION', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('formacion', TextType::class, ['label' => 'TRANS_FORMACION', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('fechaFormacion', DateType::class, ['label' => 'TRANS_FECHA_FORMACION', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('fechaUltimaRevision', DateType::class, ['label' => 'TRANS_FECHA_ULTIMA_REVISION', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('fechaNuevaRevision', DateType::class, ['label' => 'TRANS_FECHA_NUEVA_REVISION', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trabajador::class,
        ]);
    }
}
