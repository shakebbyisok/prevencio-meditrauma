<?php

namespace App\Form;

use App\Entity\EstadoTareaTecnico;
use App\Entity\TareaTecnico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TareaTecnicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('fechainicio', DateTimeType::class, ['label' => 'TRANS_FECHA_INICIO', 'translation_domain' => 'agendatecnico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('duracion', TimeType::class, ['label' => 'TRANS_DURACION', 'translation_domain' => 'agendatecnico', 'required' => TRUE, 'widget' => 'single_text', 'html5' => true])
            ->add('estado', EntityType::class, ['class' => EstadoTareaTecnico::class, 'required' => true, 'disabled' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = :anulado')
                    ->setParameter('anulado', false);
            }, 'attr' => ['class' => 'select-search']])
            ->add('descripcion', TextareaType::class, ['label' => 'TRANS_DESCRIPCION', 'translation_domain' => 'agendatecnico', 'required' => true])
            ->add('guardar', SubmitType::class)
	        ->add('eliminar', SubmitType::class, ['attr' => ['class' => 'btn-danger', 'onclick' => 'return confirm(\'¿Desea eliminar?\')']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TareaTecnico::class,
        ]);
    }
}
