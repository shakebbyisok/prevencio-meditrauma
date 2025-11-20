<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\GdocPlantillas;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EnviarCorreoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('para', TextType::class, ['label' => 'TRANS_CORREO_PARA', 'translation_domain' => 'messages', 'required' => true, 'data' => $options['destinatario']])
	        ->add('cc', TextType::class, ['label' => 'CC', 'translation_domain' => 'messages', 'required' => false])
	        ->add('cco', TextType::class, ['label' => 'CCO', 'translation_domain' => 'messages', 'data' => $options['cco'], 'required' => false])
	        ->add('asunto', TextType::class, ['label' => 'TRANS_CORREO_ASUNTO', 'translation_domain' => 'messages', 'required' => true])
	        ->add('mensaje', TextareaType::class, ['label' => 'TRANS_CORREO_MENSAJE', 'translation_domain' => 'messages', 'required' => true])
            ->add('plantilla', EntityType::class, ['class' => GdocPlantillas::class, 'required' => false, 'disabled' => false,  'choice_label' => 'nombre', 'empty_data' => null, 'mapped' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.carpeta IN (:carpeta)')
                    ->setParameter('carpeta', 8)
                    ->andWhere('u.anulado = false');
            }, 'attr' => ['class' => 'select-search']])
	        ->add('enviar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
	        'destinatario' => null,
            'cco' => null
        ]);
    }
}
