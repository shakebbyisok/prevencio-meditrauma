<?php

namespace App\Form;

use App\Entity\Protocolo;
use App\Entity\PuestoTrabajoCentro;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProtocoloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', TextType::class, ['label' => 'TRANS_PROTOCOLO_ES', 'translation_domain' => 'puestotrabajoprotocolocuestionario', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_PROTOCOLO_CA', 'translation_domain' => 'puestotrabajoprotocolocuestionario', 'required' => true])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Protocolo::class,
        ]);
    }
}
