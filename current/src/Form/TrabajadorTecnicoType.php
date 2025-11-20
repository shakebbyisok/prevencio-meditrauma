<?php

namespace App\Form;

use App\Entity\DatosBancarios;
use App\Entity\Empresa;
use App\Entity\PuestoTrabajoCentro;
use App\Entity\PuestoTrabajoGenerico;
use App\Entity\PuestoTrabajoTrabajador;
use App\Entity\Trabajador;
use Doctrine\ORM\EntityRepository;
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

class TrabajadorTecnicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',  TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('dni', TextType::class, ['label' => 'TRANS_DNI', 'translation_domain' => 'trabajadores', 'required' => false])
	        ->add('sexo', ChoiceType::class, ['choices' => ['TRANS_HOMBRE' => 1, 'TRANS_MUJER' => 2], 'translation_domain' => 'trabajadores', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
	        ->add('fechaNacimiento', DateType::class, ['label' => 'TRANS_DTNAC', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true ])
            ->add('discapacidad', CheckboxType::class, ['label' => 'TRANS_DISCAPACIDAD', 'translation_domain' => 'trabajadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('domicilio', TextType::class, ['label' => 'TRANS_DOMICILIO', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('telefono1', TextType::class, ['label' => 'TRANS_TELEFONO', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('mail', TextType::class, ['label' => 'TRANS_MAIL', 'translation_domain' => 'trabajadores', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trabajador::class
        ]);
    }
}
