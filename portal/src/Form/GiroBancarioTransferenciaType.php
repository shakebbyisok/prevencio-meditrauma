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

class GiroBancarioTransferenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('facturacion', EntityType::class, ['class' => Facturacion::class, 'required' => true, 'choice_label' => 'numeroFacturaSerie', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
		                  ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId'])
		                  ->orderBy('u.fecha', 'ASC');
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA_EXPEDICION', 'translation_domain' => 'balanceeconomico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
	        ->add('importe', NumberType::class, ['label' => 'TRANS_IMPORTE_FACTURA', 'translation_domain' => 'balanceeconomico', 'required' => true, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('cuenta', EntityType::class, ['class' => DatosBancarios::class, 'required' => true, 'choice_label' => 'cuentaEmpresa', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
			              ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GiroBancario::class,
            'empresaId' => null,
        ]);
    }
}
