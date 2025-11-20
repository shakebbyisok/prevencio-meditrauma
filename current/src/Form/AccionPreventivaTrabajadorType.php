<?php

namespace App\Form;

use App\Admin\PreventivaEmpresa;
use App\Entity\AccionPreventivaEmpresaRiesgoCausa;
use App\Entity\AccionPreventivaTrabajadorRiesgoCausa;
use App\Entity\PreventivaTrabajador;
use App\Entity\ResponsableExterno;
use App\Entity\TipoPlanificacion;
use App\Entity\Trabajador;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AccionPreventivaTrabajadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('preventivaTrabajador', EntityType::class, ['label' => 'TRANS_ACCION_PREVENTIVA', 'translation_domain' => 'evaluacion', 'class' => PreventivaTrabajador::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id in (:preventivaTrabajadorId)')
                    ->andWhere('u.anulado = false')
                    ->setParameter('preventivaTrabajadorId', $options['preventivaTrabajadorId'])
                    ->orderBy('u.descripcion', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            //Petició 01/09/2023
            //->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('descripcion', TextareaType::class, ['label' => false, 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccionPreventivaTrabajadorRiesgoCausa::class,
            'preventivaTrabajadorId' => null
        ]);
    }
}
