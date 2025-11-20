<?php

namespace App\Form;

use App\Entity\BuscadorQueries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BuscadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('nombre', TextType::class, ['label' => 'TRANS_NOMBRE_CONSULTA', 'translation_domain' => 'buscadores', 'required' => true])
	        ->add('compartida', CheckboxType::class, ['label' => 'TRANS_COMPARTIDA', 'translation_domain' => 'buscadores', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('variables', ChoiceType::class, ['choices' => $options['variables'], 'data' => $options['variablesSeleccionadas'], 'label' => false, 'mapped' => false, 'expanded' => false, 'multiple' => true, 'required' => false, /*'attr' => ['class' => 'multiselect']*/])
	        ->add('restricciones', HiddenType::class)
	        ->add('restriccionesSQL', HiddenType::class)
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BuscadorQueries::class,
	        'variables' => array(),
	        'variablesSeleccionadas' => array(),
        ]);
    }
}
