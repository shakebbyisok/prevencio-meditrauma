<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\Renovacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RenovacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('contrato', EntityType::class, ['class' => Contrato::class, 'required' => false, 'disabled' => false, 'choice_label' => 'ContratoEmpresa', 'data' => $options['contratoObj'], 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa IN (:empresa)')
			              ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fechainicio', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'renovaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fechavencimiento', DateType::class, ['label' => 'TRANS_VENCIMIENTO', 'translation_domain' => 'renovaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('renovado', CheckboxType::class, ['label' => 'TRANS_RENOVADO', 'translation_domain' => 'renovaciones', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('cancelada', CheckboxType::class, ['label' => 'TRANS_CANCELADA', 'translation_domain' => 'renovaciones', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Renovacion::class,
            'empresaId' => null,
            'empresaObj' => null,
            'contratoObj' => null
        ]);
    }
}
