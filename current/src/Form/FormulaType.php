<?php

namespace App\Form;

use App\Entity\BuscadorQueries;
use App\Entity\Formula;
use App\Entity\SerieRespuesta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormulaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO_FORMULA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
	        ->add('descripcion', TextType::class, ['label' => 'TRANS_DESCRIPCION', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formula::class,
        ]);
    }
}
