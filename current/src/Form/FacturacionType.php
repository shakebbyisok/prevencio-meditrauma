<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\Facturacion;
use App\Entity\FormaPago;
use App\Entity\SerieFactura;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('contrato', EntityType::class, ['class' => Contrato::class, 'required' => false, 'choice_label' => 'contratoEmpresa', 'data' => $options['contratoObj'], 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa in (:empresa)')
                    ->setParameter('empresa', $options['empresaId'])
                    ->orderBy('u.fechainicio', 'DESC');
            }, 'attr' => ['class' => 'select-search']])
	        ->add('numFac',  TextType::class, ['label' => 'TRANS_FACTURA', 'translation_domain' => 'facturacion', 'required' => false, 'disabled' => true])
	        ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => true, 'disabled' => false,  'choice_label' => 'empresa', 'empty_data' => null, 'data' => $options['empresaObj'], 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.id IN (:id)')
		                  ->setParameter('id', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('cifEmpresa',  TextType::class, ['label' => 'TRANS_CIF', 'translation_domain' => 'facturacion', 'mapped' => false, 'required' => false, 'disabled' => true])
	        ->add('direccionEmpresa',  TextType::class, ['label' => 'TRANS_DIRECCION', 'translation_domain' => 'facturacion', 'mapped' => false, 'required' => false, 'disabled' => true])
	        ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'facturacion', 'required' => false, 'widget' => 'single_text', 'html5' => true])
	        ->add('formaPago', EntityType::class, ['class' => FormaPago::class, 'required' => false, 'data' => $options['formaPagoObj'],  'choice_label' => 'descripcion', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('serie', EntityType::class, ['class' => SerieFactura::class, 'required' => false,  'choice_label' => 'SerieCodigo',  'empty_data' => null, 'disabled' => false, 'attr' => ['class' => 'select-search']])
	        ->add('renovacion', CheckboxType::class, ['label' => 'TRANS_FACTURA_RENOVACION', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('pagada', CheckboxType::class, ['label' => 'TRANS_PAGADA', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('cancelada', CheckboxType::class, ['label' => 'TRANS_CANCELADA', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('comisionableColaborador', CheckboxType::class, ['label' => 'TRANS_COMISIONABLE_COL', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('comisionColaborador', NumberType::class, ['label' => false, 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
//	        ->add('comisionableTecnico', CheckboxType::class, ['label' => 'TRANS_COMISIONABLE_TEC', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('comisionTecnico', NumberType::class, ['label' => false, 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
//	        ->add('comisionableComercial', CheckboxType::class, ['label' => 'TRANS_COMISIONABLE_COM', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('comisionComercial', NumberType::class, ['label' => false, 'required' => false, 'scale' => 2, 'attr' => array( 'min'  => 0, 'max'  => 999999999.99, 'step' => 0.01)])
//	        ->add('pagadaComisionColaborador', CheckboxType::class, ['label' => 'TRANS_COMISION_PAGADA_COL', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('pagadaComisionTecnico', CheckboxType::class, ['label' => 'TRANS_COMISION_PAGADA_TEC', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//	        ->add('pagadaComisionComercial', CheckboxType::class, ['label' => 'TRANS_COMISION_PAGADA_COM', 'translation_domain' => 'facturacion', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBSERVACIONES', 'translation_domain' => 'facturacion', 'required' => false])
            ->add('facturaRectificativa', EntityType::class, ['class' => Facturacion::class, 'required' => false, 'choice_label' => 'numeroFacturaSerie', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa in (:empresa)')
                    ->andWhere('u.anulado = false')
                    ->andWhere('u.serie != 6')
                    ->setParameter('empresa', $options['empresaId'])
                    ->orderBy('u.fecha', 'DESC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('facturaAsociada', EntityType::class, ['class' => Facturacion::class, 'required' => false, 'choice_label' => 'numeroFacturaSerie', 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa in (:empresa)')
                    ->andWhere('u.anulado = false')
                    ->andWhere('u.serie = 7')
                    ->setParameter('empresa', $options['empresaId'])
                    ->orderBy('u.fecha', 'DESC');
            }, 'attr' => ['class' => 'select-search']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facturacion::class,
            'empresaId' => null,
            'empresaObj' => null,
            'contratoObj' => null,
            'formaPagoObj' => null
        ]);
    }
}
