<?php

namespace App\Form;

use App\Entity\Centro;
use App\Entity\Empresa;
use App\Entity\GrupoMaquina;
use App\Entity\MaquinaEmpresa;
use App\Entity\MaquinaGenerica;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MaquinaEmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion',  TextType::class, ['label' => 'TRANS_MAQUINA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO', 'translation_domain' => 'empresas', 'required' => false])
            ->add('centro', EntityType::class, ['class' => Centro::class, 'required' => false, 'choice_label' => 'nombre', 'empty_data' => null, 'disabled' => false, 'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('u')
                    ->where('u.id IN (:id)')
                    ->setParameter('id', $options['centrosId']);
            }, 'attr' => ['class' => 'select-search']])
            ->add('grupoMaquina', EntityType::class, ['class' => GrupoMaquina::class, 'choice_label' => 'descripcion', 'label' => 'TRANS_GRUPO_MAQUINARIA', 'translation_domain' => 'empresas', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
            ->add('maquinaGenerica', EntityType::class, ['class' => MaquinaGenerica::class, 'choice_label' => 'descripcion', 'label' => 'TRANS_MAQUINARIA_GENERICA', 'translation_domain' => 'empresas', 'required' => false, 'empty_data' => null, 'placeholder' => false, 'attr' => ['class' => 'select-search']])
            ->add('fabricante',  TextType::class, ['label' => 'TRANS_FABRICANTE', 'translation_domain' => 'empresas', 'required' => false])
            ->add('modelo',  TextType::class, ['label' => 'TRANS_MODELO', 'translation_domain' => 'empresas', 'required' => false])
            ->add('numSerie',  TextType::class, ['label' => 'TRANS_NUMERO_SERIE', 'translation_domain' => 'empresas', 'required' => false])
            ->add('anyoFabricacion',  TextType::class, ['label' => 'TRANS_AÑO_FABRICACION', 'translation_domain' => 'empresas', 'required' => false])
            ->add('anyoCompra',  TextType::class, ['label' => 'TRANS_AÑO_COMPRA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('placaCaracteristica',  TextType::class, ['label' => 'TRANS_PLACA_CARACTERISTICA', 'translation_domain' => 'empresas', 'required' => false])
            ->add('marcadoCE',  TextType::class, ['label' => 'TRANS_MARCADO_CE', 'translation_domain' => 'empresas', 'required' => false])
            ->add('conformidad', CheckboxType::class, ['label' => 'TRANS_CONFORMIDAD', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('manualInstrucciones', CheckboxType::class, ['label' => 'TRANS_MANUAL_INSTRUCCIONES', 'translation_domain' => 'empresas', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('observaciones',  TextareaType::class, ['label' => 'TRANS_MENU_5', 'translation_domain' => 'empresas', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MaquinaEmpresa::class,
            'centrosId' => null
        ]);
    }
}
