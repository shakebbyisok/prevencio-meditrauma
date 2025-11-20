<?php

namespace App\Form;

use App\Entity\Facturacion;
use App\Entity\GiroBancarioDevolucion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GiroBancarioDevolucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('facturacion', EntityType::class, ['class' => Facturacion::class, 'required' => true, 'choice_label' => 'numeroFacturaSerie', 'empty_data' => null, 'disabled' => true, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.empresa = :empresa')
		                  ->andWhere('u.anulado = false')
		                  ->setParameter('empresa', $options['empresaId'])
		                  ->orderBy('u.fecha', 'ASC');
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA_EXPEDICION', 'translation_domain' => 'balanceeconomico', 'required' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('concepto', TextType::class, ['label' => 'TRANS_CONCEPTO', 'translation_domain' => 'balanceeconomico', 'required' => false])
            ->add('reciboGenerado', CheckboxType::class, ['label' => 'TRANS_RECIBO_GENERADO', 'translation_domain' => 'balanceeconomico', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBS', 'translation_domain' => 'balanceeconomico', 'required' => false])
            ->add('importe', NumberType::class, ['label' => 'TRANS_IMPORTE_FACTURA', 'translation_domain' => 'balanceeconomico', 'required' => true, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GiroBancarioDevolucion::class,
            'empresaId' => null,
	        'empresaObj' => null
        ]);
    }
}
