<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\ContratoPago;
use App\Entity\FormaPago;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PagoPendienteType extends AbstractType
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
	        ->add('porcentaje',  TextType::class, ['label' => 'TRANS_PORCENTAJE', 'translation_domain' => 'pagopendiente', 'required' => false])
	        ->add('vencimientoMeses', NumberType::class, ['label' => 'TRANS_VENCIMIENTO', 'translation_domain' => 'pagopendiente', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('importeSinIva', NumberType::class, ['label' => 'TRANS_IMPORTE_SIN_IVA', 'translation_domain' => 'pagopendiente', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('importeExentoIva', NumberType::class, ['label' => 'TRANS_EXENTO_IVA', 'translation_domain' => 'pagopendiente', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('importeSujetoIva', NumberType::class, ['label' => 'TRANS_SUJETO_IVA', 'translation_domain' => 'pagopendiente', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('importeIva', NumberType::class, ['label' => 'TRANS_IVA', 'translation_domain' => 'pagopendiente', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('importeTotal', NumberType::class, ['label' => 'TRANS_TOTAL', 'translation_domain' => 'pagopendiente', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('facturado', CheckboxType::class, ['label' => 'TRANS_FACTURADO', 'translation_domain' => 'pagopendiente', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContratoPago::class,
            'empresaId' => null,
            'empresaObj' => null,
            'contratoObj' => null
        ]);
    }
}
