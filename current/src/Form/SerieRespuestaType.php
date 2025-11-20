<?php

namespace App\Form;

use App\Entity\BuscadorQueries;
use App\Entity\Indicador;
use App\Entity\SerieRespuesta;
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

class SerieRespuestaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO_SERIE', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
	        ->add('descripcion', TextType::class, ['label' => 'TRANS_DESCRIPCION', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false])
            ->add('indicador', EntityType::class, ['class' => Indicador::class, 'required' => true, 'disabled' => false,  'choice_label' => 'descripcion', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SerieRespuesta::class,
        ]);
    }
}
