<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\Trabajador;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrabajadorImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//	        ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => true, 'disabled' => false, 'choice_label' => 'empresa', 'data' => $options['empresaObj'], 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
//		        return $er->createQueryBuilder('u')
//		                  ->where('u.id IN (:id)')
//		                  ->setParameter('id', $options['empresaId']);
//	        }, 'attr' => ['class' => 'select-search']])
            ->add('fichero',  FileType::class, ['label' => 'TRANS_FICHERO', 'translation_domain' => 'trabajadores', 'required' => true])
            ->add('importar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
