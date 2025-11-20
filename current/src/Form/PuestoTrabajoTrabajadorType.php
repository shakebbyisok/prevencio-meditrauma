<?php

namespace App\Form;

use App\Entity\Centro;
use App\Entity\Empresa;
use App\Entity\Facturacion;
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

class PuestoTrabajoTrabajadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => true, 'choice_label' => 'empresa', 'data' => $options['empresaObj'], 'empty_data' => null, 'placeholder' => '', 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['empresasId'])
                    ->orderBy('u.empresa', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('puestoTrabajo', EntityType::class, ['class' => PuestoTrabajoCentro::class, 'required' => true, 'choice_label' => 'descripcion', 'data' => $options['puestoTrabajoObj'], 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa = :empresa')
                    ->andWhere('u.anulado = false')
                    ->setParameter('empresa', $options['empresaId']);
            }, 'attr' => ['class' => 'select-search']])
            ->add('centro', EntityType::class, ['label' => 'TRANS_CENTRO', 'translation_domain' => 'evaluacion', 'class' => Centro::class, 'required' => false, 'choice_label' => 'nombre', 'data' => $options['centroTrabajoObj'], 'empty_data' => null, 'placeholder' => '', 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['centrosId']);
            }, 'attr' => ['class' => 'select-search']])
            ->add('trabajador', EntityType::class, ['class' => Trabajador::class, 'required' => true, 'choice_label' => 'nombre', 'empty_data' => null, 'placeholder' => '', 'data' => $options['trabajadorObj'], 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['trabajadoresId'])
                    ->orderBy('u.nombre', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('fechaAlta', DateType::class, ['label' => 'TRANS_FECHA_ALTA', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('fechaBaja', DateType::class, ['label' => 'TRANS_FECHA_BAJA', 'translation_domain' => 'trabajadores', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('delegadoPrevencion', CheckboxType::class, ['label' => 'TRANS_DELEGADO_PREVENCION', 'translation_domain' => 'evaluacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('delegadoPersonal', CheckboxType::class, ['label' => 'TRANS_DELEGADO_PERSONAL', 'translation_domain' => 'evaluacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('delegadoComite', CheckboxType::class, ['label' => 'TRANS_DELEGADO_COMITE', 'translation_domain' => 'evaluacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('dedicacion', TextareaType::class, ['label' => 'TRANS_DEDICACION', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'evaluacion', 'required' => false])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PuestoTrabajoTrabajador::class,
            'trabajadoresId' => null,
            'puestoTrabajoId' => null,
            'centrosId' => null,
            'empresaId' => null,
            'puestoTrabajoObj' => null,
            'centroTrabajoObj' => null,
            'empresasId' => null,
            'empresaObj' => null,
            'trabajadorObj' => null
        ]);
    }
}
