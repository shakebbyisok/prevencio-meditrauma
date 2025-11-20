<?php

namespace App\Form;

use App\Entity\ConsejoMedico;
use App\Entity\Cuestionario;
use App\Entity\Formula;
use App\Entity\Pregunta;
use App\Entity\SerieRespuesta;
use App\Entity\TipoCuestionario;
use App\Entity\TipoRespuesta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuestionarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO_CUESTIONARIO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcion', TextType::class, ['label' => 'TRANS_CUESTIONARIO_ES', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_CUESTIONARIO_CA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('tipoCuestionario', EntityType::class, ['class' => TipoCuestionario::class, 'required' => false, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('orden', NumberType::class, ['label' => 'TRANS_ORDEN', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cuestionario::class,
        ]);
    }
}
