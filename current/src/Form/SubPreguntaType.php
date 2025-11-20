<?php

namespace App\Form;

use App\Entity\BuscadorQueries;
use App\Entity\Formula;
use App\Entity\SerieRespuesta;
use App\Entity\SubPregunta;
use App\Entity\TipoRespuesta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SubPreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('orden', TextType::class, ['label' => 'TRANS_ORDEN', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('tipo', ChoiceType::class, ['label' => 'TRANS_TIPO_DATO', 'choices' => ['' => null, '0' => 0, '1' => 1], 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
            ->add('descripcion', TextType::class, ['label' => 'TRANS_TEXTO_PREGUNTA_ES', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_TEXTO_PREGUNTA_CA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubPregunta::class,
        ]);
    }
}
