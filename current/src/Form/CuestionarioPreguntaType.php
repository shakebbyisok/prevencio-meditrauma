<?php

namespace App\Form;

use App\Entity\CuestionarioPregunta;
use App\Entity\Pregunta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuestionarioPreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupo', NumberType::class, ['label' => 'TRANS_GRUPO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('orden', NumberType::class, ['label' => 'TRANS_ORDEN', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('pregunta', EntityType::class, ['class' => Pregunta::class, 'required' => true, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CuestionarioPregunta::class,
        ]);
    }
}
