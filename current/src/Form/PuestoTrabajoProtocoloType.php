<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\Protocolo;
use App\Entity\PuestoTrabajoCentro;
use App\Entity\PuestoTrabajoProtocolo;
use App\Entity\TipoRespuesta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PuestoTrabajoProtocoloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('protocolo', EntityType::class, ['class' => Protocolo::class, 'required' => true, 'disabled' => false, 'data' => $options['protocolos'], 'expanded'=>true, 'mapped' => false, 'multiple' => true,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false')
                    ->orderBy('u.descripcion', 'ASC');
            },])
            ->add('actualizado', CheckboxType::class, ['label' => 'TRANS_ACTUALIZADO', 'translation_domain' => 'puestotrabajoprotocolocuestionario', 'data' => $options['actualizadoSn'], 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'protocolos' => null,
            'actualizadoSn' => null
        ]);
    }
}
