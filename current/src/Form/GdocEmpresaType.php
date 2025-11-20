<?php

namespace App\Form;

use App\Entity\GdocEmpresa;
use App\Entity\GdocEmpresaCarpeta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GdocEmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'TRANS_NOMBRE_FICHERO', 'translation_domain' => 'gdoc', 'required' => true])
	        ->add('media', FileType::class, ['label' => 'TRANS_FICHERO', 'translation_domain' => 'gdoc', 'required' => true])
	        ->add('carpeta', EntityType::class, ['class' => GdocEmpresaCarpeta::class, 'required' => true, 'choice_label' => 'nombre', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa = :empresaId')
                    ->orWhere('u.compartida = true')
                    ->setParameter('empresaId', $options['empresaId']);
            }, 'attr' => ['class' => 'select-search']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GdocEmpresa::class,
            'empresaId' => null
        ]);
    }
}
