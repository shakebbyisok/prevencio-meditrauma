<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\Renovacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RenovacionRenovarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('documentoRenovacion', ChoiceType::class, ['choices' => ['TRANS_RENOVACION_CON_CONTRATO' => 1, 'TRANS_RENOVACION_CON_FACTURA' => 2, 'TRANS_RENOVACION_TACITAS' => 3, 'TRANS_RENOVACION_CON_PROGRAMA_ANUAL' => 4], 'label' => false, 'translation_domain' => 'renovaciones', 'expanded' => true, 'multiple' => false, 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('tipoFechaRenovacion', ChoiceType::class, ['choices' => ['TRANS_MANTENER_FECHA_RENOVACION' => 1, 'TRANS_UTILIZAR_SIGUIENTE_FECHA_RENOVACION' => 2], 'label' => false, 'translation_domain' => 'renovaciones', 'expanded' => true, 'multiple' => false, 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('fechaRenovacion', DateType::class, ['label' => false, 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fraccionamientoPago', ChoiceType::class, ['choices' => ['TRANS_MANTENER_MISMO_NUMERO_PAGOS' => 1, 'TRANS_UTILIZAR_FRACC_PAGOS' => 2, 'TRANS_GENERAR_NUMERO_PAGOS' => 3], 'label' => false, 'translation_domain' => 'renovaciones', 'expanded' => true, 'multiple' => false, 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('numeroPagos', NumberType::class, ['label' => false, 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('planificacionesVigSalud', CheckboxType::class, ['label' => 'TRANS_GENERAR_NUEVA_PLANIFICACION', 'translation_domain' => 'renovaciones', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('contrato', EntityType::class, ['class' => Contrato::class, 'required' => false,  'choice_label' => 'ContratoEmpresa', 'placeholder' => '',  'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
	        return $er->createQueryBuilder('u')
	              ->where('u.empresa IN (:empresa)')
		           ->andWhere('u.anulado = false')
	              ->setParameter('empresa', $options['empresaId']);
            }, 'attr' => ['class' => 'select-search']])
	        ->add('fechainicio', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'renovaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fechavencimiento', DateType::class, ['label' => 'TRANS_VENCIMIENTO', 'translation_domain' => 'renovaciones', 'required' => false, 'widget' => 'single_text', 'html5' => true])
//	        ->add('renovado', CheckboxType::class, ['label' => 'TRANS_RENOVADO', 'translation_domain' => 'renovaciones', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('cancelada', CheckboxType::class, ['label' => 'TRANS_CANCELADA', 'translation_domain' => 'renovaciones', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
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
