<?php

namespace App\Form;

use App\Entity\DatosBancarios;
use App\Entity\Empresa;
use App\Entity\EntidadBancaria;
use App\Entity\FormaPago;
use App\Entity\Pais;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DatosBancariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('empresa', EntityType::class, ['class' => Empresa::class, 'required' => false, 'disabled' => true, 'choice_label' => 'Empresa', 'data' => $options['empresaObj'], 'placeholder' => '', 'empty_data' => null, 'query_builder' => function (EntityRepository $er) use ($options) {
		        return $er->createQueryBuilder('u')
		                  ->where('u.id = :id')
		                  ->setParameter('id', $options['empresaId']);
	        }, 'attr' => ['class' => 'select-search']])
	        ->add('entidadBancaria', EntityType::class, ['class' => EntidadBancaria::class, 'required' => true,  'choice_label' => 'nrbDescripcion', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('oficina', TextType::class, ['label' => 'TRANS_OFICINA', 'translation_domain' => 'datosbancarios', 'required' => true, 'attr' => ['maxlength' => '4']])
	        ->add('numCuenta', TextType::class, ['label' => 'TRANS_NUMERO_CUENTA', 'translation_domain' => 'datosbancarios', 'required' => true, 'attr' => ['maxlength' => '10']])
	        ->add('dc', TextType::class, ['label' => 'TRANS_DC', 'translation_domain' => 'datosbancarios', 'required' => true, 'attr' => ['maxlength' => '2']])
	        ->add('principal', CheckboxType::class, ['label' => 'TRANS_PRINCIPAL', 'translation_domain' => 'datosbancarios', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
	        ->add('pais', EntityType::class, ['class' => Pais::class, 'label' => 'TRANS_CODIGO_PAIS', 'translation_domain' => 'datosbancarios', 'required' => true,  'choice_label' => 'descripcion', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('ibanDigital', TextType::class, ['label' => 'TRANS_IBAN_DIGITAL', 'translation_domain' => 'datosbancarios', 'required' => false])
	        ->add('ibanPapel', TextType::class, ['label' => 'TRANS_IBAN_PAPEL', 'translation_domain' => 'datosbancarios', 'required' => false])
	        ->add('bic', TextType::class, ['label' => 'TRANS_BIC', 'translation_domain' => 'datosbancarios', 'required' => false])
	        ->add('formaPago', EntityType::class, ['class' => FormaPago::class, 'label' => 'TRANS_FORMA_PAGO', 'translation_domain' => 'datosbancarios', 'required' => false,  'choice_label' => 'descripcion', 'placeholder' => '',  'empty_data' => null, 'attr' => ['class' => 'select-search']])
	        ->add('diaPago', NumberType::class, ['label' => 'TRANS_DIA_PAGO', 'translation_domain' => 'datosbancarios', 'required' => false, 'attr' => array( 'min'  => 0, 'max'  => 9999)])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DatosBancarios::class,
            'empresaId' => null,
            'empresaObj' => null
        ]);
    }
}
