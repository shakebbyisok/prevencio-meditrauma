<?php

namespace App\Form;

use App\Entity\MetodologiaEvaluacion;
use App\Entity\TipoEvaluacion;
use App\Entity\Centro;
use App\Entity\Empresa;
use App\Entity\Evaluacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EvaluacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('empresa', EntityType::class, ['label' => 'TRANS_EMPRESA', 'translation_domain' => 'evaluacion', 'class' => Empresa::class, 'required' => true, 'disabled' => true,  'choice_label' => 'empresa', 'empty_data' => null, 'data' => $options['empresaObj'], 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['empresaId']);
            }, 'attr' => ['class' => 'select-search']])
            ->add('centro', EntityType::class, ['label' => 'TRANS_CENTRO', 'translation_domain' => 'evaluacion', 'class' => Centro::class, 'required' => false, 'mapped' => false, 'expanded'=>true, 'multiple'=>true, 'choice_label' => 'direccion', 'empty_data' => null, 'data' => $options['centroObj'], 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['centrosId']);
            },])
            ->add('fechaInicio', DateType::class, ['label' => 'TRANS_FECHA_INICIO', 'translation_domain' => 'evaluacion', 'required' => false, 'widget' => 'single_text', 'html5' => true])
//            ->add('fechaFin', DateType::class, ['label' => 'TRANS_FECHA_FIN', 'translation_domain' => 'evaluacion', 'required' => false, 'disabled' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('fechaProxima', DateType::class, ['label' => 'TRANS_FECHA_PROXIMA', 'translation_domain' => 'evaluacion', 'required' => false, 'widget' => 'single_text', 'html5' => true])
            ->add('metodologia', EntityType::class, ['label' => 'TRANS_METODOLOGIA', 'translation_domain' => 'evaluacion', 'class' => MetodologiaEvaluacion::class, 'required' => true, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('tipoEvaluacion', EntityType::class, ['label' => 'TRANS_TIPO', 'translation_domain' => 'evaluacion', 'class' => TipoEvaluacion::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('descripcion', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evaluacion::class,
            'empresaId' => null,
            'empresaObj' => null,
            'centroObj' => null,
            'centrosId' => null
        ]);
    }
}
