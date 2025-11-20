<?php

namespace App\Form;

use App\Entity\ConsejoMedico;
use App\Entity\Pregunta;
use App\Entity\Respuesta;
use App\Entity\SerieRespuesta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RespuestaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serieRespuesta', EntityType::class, ['class' => SerieRespuesta::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('pregunta', EntityType::class, ['class' => Pregunta::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
	        ->add('descripcion', TextType::class, ['label' => 'TRANS_RESPUESTA_ES', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_RESPUESTA_CA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('sub', NumberType::class, ['label' => 'TRANS_SUB', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('valorDesde', TextType::class, ['label' => 'TRANS_VALOR_DESDE', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('valorDesdeNumerico', NumberType::class, ['label' => 'TRANS_VALOR_DESDE_NUMERICO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'mapped' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('valorHasta', TextType::class, ['label' => 'TRANS_VALOR_HASTA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('valorHastaNumerico', NumberType::class, ['label' => 'TRANS_VALOR_HASTA_NUMERICO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'mapped' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('informeMedico', CheckboxType::class, ['label' => 'TRANS_INFORME_MEDICO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('informeFinal', CheckboxType::class, ['label' => 'TRANS_INFORME_FINAL', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('textoSiValorCorresponde', TextType::class, ['label' => 'TRANS_TEXTO_SI_CORRESPONDE', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('consejoMedico', EntityType::class, ['class' => ConsejoMedico::class, 'required' => false, 'disabled' => false,  'choice_label' => 'CodigoDescripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false')
                    ->orderBy('u.codigo', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('respuestaProblemas', TextType::class, ['label' => 'TRANS_RESPUESTA_PROBLEMAS', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Respuesta::class,
        ]);
    }
}
