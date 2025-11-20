<?php

namespace App\Form;

use App\Entity\BalanceEconomicoEntrada;
use App\Entity\Facturacion;
use App\Entity\FormaPago;
use App\Entity\Pais;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BalanceEconomicoEntradaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facturacion', EntityType::class, ['class' => Facturacion::class, 'required' => false, 'choice_label' => 'numeroFacturaSerie', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.empresa = :empresa')
                    ->andWhere('u.anulado = false')
                    ->setParameter('empresa', $options['empresaId'])
                    ->orderBy('u.fecha', 'ASC');
            }, 'attr' => ['class' => 'select-search']])
            ->add('fecha', DateType::class, ['label' => 'TRANS_FECHA', 'translation_domain' => 'facturacion', 'required' => true, 'widget' => 'single_text', 'html5' => true])
            ->add('concepto', TextType::class, ['label' => 'TRANS_CONCEPTO', 'translation_domain' => 'facturacion', 'required' => true])
            ->add('importe', NumberType::class, ['label' => 'TRANS_IMPORTE', 'translation_domain' => 'facturacion', 'required' => true, 'scale' => 3, 'attr' => array( 'min'  => -9999.99, 'max'  => 9999.99, 'step' => 0.01)])
            ->add('tipo', ChoiceType::class, ['choices' => ['Debe' => 1, 'Haber' => 2], 'translation_domain' => 'trabajadores', 'required' => true, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
            ->add('formaPago', EntityType::class, ['class' => FormaPago::class, 'required' => true, 'choice_label' => 'descripcion', 'placeholder' => '', 'empty_data' => null, 'attr' => ['class' => 'select-search']])
            ->add('observaciones', TextareaType::class, ['label' => 'TRANS_OBSERVACIONES', 'translation_domain' => 'facturacion', 'required' => false])
            ->add('pagoConfirmado', CheckboxType::class, ['label' => 'TRANS_PAGO_CONFIRMADO', 'translation_domain' => 'balanceeconomico', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BalanceEconomicoEntrada::class,
            'empresaId' => null
        ]);
    }
}
