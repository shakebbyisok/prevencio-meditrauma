<?php

namespace App\Form;

use App\Entity\DatosBancarios;
use App\Entity\Empresa;
use App\Entity\Facturacion;
use App\Entity\FacturacionVencimiento;
use App\Entity\FormaPago;
use App\Entity\GiroBancario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturacionVencimientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('facturaAsociada', EntityType::class, ['class' => Facturacion::class, 'required' => true, 'choice_label' => 'numeroFacturaSerie', 'empty_data' => null, 'disabled' => true, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
		                  ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId'])
		                  ->orderBy('u.fecha', 'ASC');
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA_VENCIMIENTO', 'translation_domain' => 'balanceeconomico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
	        ->add('importe', NumberType::class, ['label' => 'TRANS_IMPORTE_FACTURA', 'translation_domain' => 'balanceeconomico', 'required' => true, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
            ->add('concepto', TextType::class, ['label' => 'TRANS_CONCEPTO', 'translation_domain' => 'balanceeconomico', 'required' => false])
            ->add('formaPago', EntityType::class, ['class' => FormaPago::class, 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('confirmado', CheckboxType::class, ['label' => 'TRANS_PAGO_CONFIRMADO', 'translation_domain' => 'balanceeconomico', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'balanceeconomico', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FacturacionVencimiento::class,
            'empresaId' => null,
	        'empresaObj' => null
        ]);
    }
}
