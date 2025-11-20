<?php

namespace App\Form;

use App\Entity\Causa;
use App\Entity\GrupoRiesgo;
use App\Entity\Riesgo;
use App\Entity\TipoPlanificacion;
use App\Entity\TipoResponsable;
use App\Entity\Trabajador;
use App\Entity\ValorRiesgo;
use App\Entity\Probabilidad;
use App\Entity\RiesgoCausaEvaluacion;
use App\Entity\Severidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RiesgoCausaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('grupoRiesgo', EntityType::class, ['label' => 'TRANS_GRUPO', 'translation_domain' => 'evaluacion', 'class' => GrupoRiesgo::class, 'required' => false, 'disabled' => false, 'choice_label' => 'grupoRiesgoCodigo', 'empty_data' => null, 'data' => $options['grupoRiesgoObj'], 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.anulado = false')
//                    ->orderBy('u.codigo', 'ASC');
//                }, 'attr' => ['class' => 'select-search']])
            ->add('riesgo', EntityType::class, ['label' => 'TRANS_RIESGO', 'translation_domain' => 'evaluacion', 'class' => Riesgo::class, 'required' => false, 'disabled' => false,  'choice_label' => 'riesgoCodigo', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.grupoRiesgo IN (:grupoRiesgoId)')
                    ->andWhere('u.anulado = false')
                    ->setParameter('grupoRiesgoId', $options['grupoRiesgoId'])
                    ->orderBy('u.codigo', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('causa', EntityType::class, ['label' => 'TRANS_CAUSA', 'translation_domain' => 'evaluacion', 'class' => Causa::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('severidad', EntityType::class, ['label' => 'TRANS_SEVERIDAD', 'translation_domain' => 'evaluacion', 'class' => Severidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.metodologia IN (:metodologia)')
                    ->setParameter('metodologia', $options['metodologia']);
            }])
            ->add('probabilidad', EntityType::class, ['label' => 'TRANS_PROBABILIDAD', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.metodologia IN (:metodologia)')
                    ->setParameter('metodologia', $options['metodologia']);
            }])
//            ->add('actividad', EntityType::class, ['label' => 'TRANS_ACTIVIDAD', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
//            ->add('consecuencia', EntityType::class, ['label' => 'TRANS_CONSECUENCIA', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
//            ->add('danyo', EntityType::class, ['label' => 'TRANS_DAÑO', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
//            ->add('exposicion', EntityType::class, ['label' => 'TRANS_EXPOSICION', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
//            ->add('factorCoste', EntityType::class, ['label' => 'TRANS_FACTOR_COSTE', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
//            ->add('gradoCorreccion', EntityType::class, ['label' => 'TRANS_GRADO_CORRECCION', 'translation_domain' => 'evaluacion', 'class' => Probabilidad::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.metodologia IN (:metodologia)')
//                    ->setParameter('metodologia', $options['metodologia']);
//            }])
            ->add('valorRiesgo', EntityType::class, ['label' => 'TRANS_VALOR_RIESGO', 'translation_domain' => 'evaluacion', 'class' => ValorRiesgo::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null])
            ->add('observacionCausa', TextareaType::class, ['label' => false, 'required' => false])
            ->add('tipoPlanificacion', EntityType::class, ['label' => 'TRANS_TIPO_PLANIFICACION', 'translation_domain' => 'evaluacion', 'class' => TipoPlanificacion::class, 'data' => $options['tipoPlanificacion'], 'required' => false, 'mapped' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('fechaPrevista', DateType::class, ['label' => 'TRANS_FECHA_PREVISTA', 'translation_domain' => 'evaluacion', 'required' => false, 'data' => $options['fechaPrevista'], 'widget' => 'single_text', 'html5' => true, 'mapped' => false])
            ->add('fechaRealizacion', DateType::class, ['label' => 'TRANS_FECHA_REALIZACION', 'translation_domain' => 'evaluacion', 'required' => false, 'data' => $options['fechaRealizacion'], 'widget' => 'single_text', 'html5' => true, 'mapped' => false])
            ->add('costePrevisto', TextType::class, ['label' => 'TRANS_COSTE_PREVISTO', 'translation_domain' => 'evaluacion', 'required' => false, 'data' => $options['coste'], 'mapped' => false])
//            ->add('responsable', EntityType::class, ['label' => 'TRANS_RESPONSABLE', 'translation_domain' => 'evaluacion', 'class' => Trabajador::class, 'required' => false, 'data' => $options['responsable'], 'mapped' => false, 'disabled' => false,  'choice_label' => 'nombre', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//                return $er->createQueryBuilder('u')
//                    ->where('u.id IN (:id)')
//                    ->setParameter('id', $options['trabajadorId']);
//            }, 'attr' => ['class' => 'select-search']])
            ->add('responsable', TextType::class, ['label' => 'TRANS_RESPONSABLE', 'translation_domain' => 'evaluacion', 'required' => false, 'disabled' => false, 'data' => $options['responsable'], 'mapped' => false])
            ->add('trabajadores', CheckboxType::class, ['label' => 'Trabajadores/as', 'required' => false, 'mapped' => false, 'disabled' => false, 'data' => $options['trabajadoresSn'], 'attr' => ['class' => 'form-check-input-styled']])
            ->add('finalizado', CheckboxType::class, ['label' => 'TRANS_FINALIZADO', 'translation_domain' => 'evaluacion', 'required' => false, 'disabled' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RiesgoCausaEvaluacion::class,
            'metodologia' => null,
            'grupoRiesgoId' => null,
            'grupoRiesgoObj' => null,
            'trabajadorId' => null,
            'tipoPlanificacion' => null,
            'fechaPrevista' => null,
            'fechaRealizacion' => null,
            'coste' => null,
            'responsable' => null,
            'trabajadoresSn' => null
        ]);
    }
}
