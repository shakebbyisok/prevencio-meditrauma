<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\Centro;
use App\Entity\FaseContrato;
use App\Entity\TipoContrato;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => false,  'choice_label' => 'Empresa', 'data' => $options['empresaObj'], 'disabled' => false,  'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.id IN (:id)')
		                  ->setParameter('id', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
            ->add('contrato', TextType::class, ['label' => 'TRANS_CONTRATO', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('origen', TextType::class, ['label' => 'TRANS_ORIGEN', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('referencia', TextType::class, ['label' => 'TRANS_REFERENCIA', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('fechainicio', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'contratos', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('fechavencimiento', DateType::class, ['label' => 'TRANS_VENCIMIENTO', 'translation_domain' => 'contratos', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('duracionRenovacion', TextType::class, ['label' => 'TRANS_DURACION', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('tipoContrato', EntityType::class, ['class' => TipoContrato::class, 'required' => false,  'choice_label' => 'descripcion', 'translation_domain' => 'contratos', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('faseSituacion', EntityType::class, ['class' => FaseContrato::class, 'label' => 'TRANS_FASE_SITUACION', 'translation_domain' => 'contratos', 'required' => false, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('nombreApellidos', TextType::class, ['label' => 'TRANS_NOMBRE_APELLIDOS', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('dni', TextType::class, ['label' => 'TRANS_DNI', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('calidad', TextType::class, ['label' => 'TRANS_CALIDAD_DE', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('centro', EntityType::class, ['class' => Centro::class, 'required' => false, 'choice_label' => 'nombre', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('trabajadoresPrevencionTecnica', NumberType::class, ['label' => 'TRANS_PREVENCION_TECNICA', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('trabajadoresVigilanciaSalud', NumberType::class, ['label' => 'TRANS_VIGILANCIA_SALUD', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('trabajadoresOtros', NumberType::class, ['label' => 'TRANS_OTROS_SERV', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('visitasConcertadas', NumberType::class, ['label' => 'TRANS_VISITAS_CONCERTADAS', 'translation_domain' => 'contratos', 'required' => false])
	        ->add('importeContrato', NumberType::class, ['label' => 'TRANS_IMPORTE_CONTRATO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 3, 'attr' => array( 'min'  => 0, 'max'  => 999999999.9999, 'step' => 0.001)])
	        ->add('importeExentoIva', NumberType::class, ['label' => 'TRANS_IMPORTE_EXENTO_IVA', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 3, 'attr' => array( 'min'  => 0, 'max'  => 999999999.9999, 'step' => 0.001)])
	        ->add('importeSujetoIva', NumberType::class, ['label' => 'TRANS_IMPORTE_CONTRATO_SUJETO_IVA', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 3, 'attr' => array( 'min'  => 0, 'max'  => 999999999.9999, 'step' => 0.001)])
	        ->add('importeIva', NumberType::class, ['label' => 'TRANS_IMPORTE_IVA', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 3, 'attr' => array( 'min'  => 0, 'max'  => 9999.9999, 'step' => 0.001)])
	        ->add('numeroPagos', NumberType::class, ['label' => 'TRANS_NUMERO_PAGOS', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('porcentajePago', NumberType::class, ['label' => 'TRANS_PORCENTAJE_PAGO', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('vencimientoPago', NumberType::class, ['label' => 'TRANS_VENCIMIENTO_PAGOS', 'translation_domain' => 'contratos', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('textoFormaPago', TextareaType::class, ['label' => false, 'required' => false])
	        ->add('contratoComiCol', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_COL', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aCol', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucCol', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('contratoComiTec', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_TEC', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aTec', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucTec', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('contratoComiCom', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_COM', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aCom', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucCom', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('contratoComiColCopy', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_COL', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aColCopy', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucColCopy', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('contratoComiTecCopy', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_TEC', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aTecCopy', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucTecCopy', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('contratoComiComCopy', CheckboxType::class, ['label' => 'TRANS_CONTRATO_COMI_COM', 'translation_domain' => 'contratos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('comision1aComCopy', NumberType::class, ['label' => 'TRANS_COMISION_1_AÑO', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('comisionASucComCopy', NumberType::class, ['label' => 'TRANS_AÑOS_SUCESIVOS', 'translation_domain' => 'contratos', 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
	        'empresaId' => null,
	        'empresaObj' => null
        ]);
    }
}
