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
use Symfony\Component\Form\Extension\Core\Type\DateType;


class GdocEmpresaType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Peticio 01/09/2023
        $builder
            ->add('media', FileType::class, ['label' => 'TRANS_FICHERO', 'translation_domain' => 'gdoc', 'required' => true])
            ->add('observaciones', TextType::class, ['label' => 'OBSERVACIONES', 'translation_domain' => 'gdoc', 'required' => false])
            ->add('fechaProxVisita', DateType::class, ['label' => 'Fecha', 'translation_domain' => 'gdoc', 'required' => true])
            ->add('carpeta', EntityType::class, ['class' => GdocEmpresaCarpeta::class, 'required' => true, 'choice_label' => 'nombre', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa = :empresaId')
                    ->orWhere('u.compartida = true')
                    ->andWhere('u.padre = :padreId') // Agrega esta condición
                    ->setParameter('empresaId', $options['empresaId'])
                    ->setParameter('padreId', 25);
            }, 'attr' => ['style' => 'display: none']])
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
