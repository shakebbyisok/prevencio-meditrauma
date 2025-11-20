<?php

namespace App\Form;

use App\Entity\GrupoEmpresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GrupoEmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoEmpresa',  TextType::class, ['label' => 'TRANS_NOMBRE', 'translation_domain' => 'grupoempresa', 'required' => false])
            ->add('descripcion', TextType::class, ['label' => 'TRANS_DESCRIPCION', 'translation_domain' => 'grupoempresa', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GrupoEmpresa::class,
        ]);
    }
}
