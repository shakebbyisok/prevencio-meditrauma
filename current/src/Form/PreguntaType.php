<?php

namespace App\Form;

use App\Entity\ConsejoMedico;
use App\Entity\Formula;
use App\Entity\IndicarHistorico;
use App\Entity\Pregunta;
use App\Entity\SerieRespuesta;
use App\Entity\TipoRespuesta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('descripcion', TextType::class, ['label' => 'TRANS_TEXTO_PREGUNTA_ES', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_TEXTO_PREGUNTA_CA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('tipoRespuesta', EntityType::class, ['class' => TipoRespuesta::class, 'required' => true, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('serieRespuesta', EntityType::class, ['class' => SerieRespuesta::class, 'required' => false, 'disabled' => false,  'choice_label' => 'codigoDescSerie', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('formula', EntityType::class, ['class' => Formula::class, 'required' => false, 'disabled' => false,  'choice_label' => 'codigoDesc', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('respuestaNormal', TextType::class, ['label' => 'TRANS_RESPUESTA_NORMAL', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('valorPorDefecto', TextareaType::class, ['label' => 'TRANS_VALOR_POR_DEFECTO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('familia', TextType::class, ['label' => 'TRANS_FAMILIA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('subFamilia', TextType::class, ['label' => 'TRANS_SUB_FAMILIA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('indicarHistorico', EntityType::class, ['class' => IndicarHistorico::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pregunta::class,
        ]);
    }
}
