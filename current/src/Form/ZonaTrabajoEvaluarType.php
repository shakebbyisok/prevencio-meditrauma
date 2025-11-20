<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\Evaluacion;
use App\Entity\PuestoTrabajoCentro;
use App\Entity\PuestoTrabajoEvaluacion;
use App\Entity\ZonaTrabajoEvaluacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ZonaTrabajoEvaluarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('trabajadores', TextType::class, ['label' => 'TRANS_NUMERO_TRABAJADORES', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('tarea', TextareaType::class, ['label' => 'Descripción', 'required' => false, 'mapped' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ZonaTrabajoEvaluacion::class,
        ]);
    }
}
