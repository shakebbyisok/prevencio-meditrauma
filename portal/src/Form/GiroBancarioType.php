<?php

namespace App\Form;

use App\Entity\DatosBancarios;
use App\Entity\Empresa;
use App\Entity\Facturacion;
use App\Entity\GiroBancario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GiroBancarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => false,  'choice_label' => 'empresa', 'empty_data' => null, 'disabled' => true, 'mapped' => false, 'data' => $options['empresaObj'], 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.id = :id')
		                  ->setParameter('id', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('facturacion', EntityType::class, ['class' => Facturacion::class, 'required' => true, 'choice_label' => 'numeroFacturaSerie', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
		                  ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId'])
		                  ->orderBy('u.fecha', 'ASC');
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA_EXPEDICION', 'translation_domain' => 'balanceeconomico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
	        ->add('importeFactura', NumberType::class, ['label' => 'TRANS_IMPORTE_FACTURA', 'translation_domain' => 'balanceeconomico', 'mapped' => false, 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('vencimiento', DateType::class, ['label' => 'TRAN_FECHA_VENCIMIENTO', 'translation_domain' => 'balanceeconomico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
	        ->add('importe', NumberType::class, ['label' => 'TRANS_IMPORTE_FACTURA', 'translation_domain' => 'balanceeconomico', 'required' => true, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('cuenta', EntityType::class, ['class' => DatosBancarios::class, 'required' => true, 'choice_label' => 'cuentaEmpresa', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
			              ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('giroFechaInicio', DateType::class, ['label' => 'TRANS_FECHA_INICIO', 'translation_domain' => 'balanceeconomico', 'mapped' => false, 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('giroRealizar', NumberType::class, ['label' => 'TRANS_REALIZAR', 'translation_domain' => 'balanceeconomico', 'required' => false, 'mapped' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('giroCada', NumberType::class, ['label' => 'TRANS_UNO_CADA', 'translation_domain' => 'balanceeconomico', 'required' => false, 'mapped' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('giroFrecuencia', ChoiceType::class, ['choices' => ['TRANS_DIAS' => 0, 'TRANS_MESES' => 1, 'TRANS_AÑOS' => 2], 'label' => false, 'translation_domain' => 'balanceeconomico', 'mapped' => false, 'expanded' => true, 'multiple' => false, 'required' => false, 'empty_data' => null, 'placeholder' => false])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GiroBancario::class,
            'empresaId' => null,
	        'empresaObj' => null
        ]);
    }
}
