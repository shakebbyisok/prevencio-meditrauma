<?php

namespace App\Form;

use App\Entity\BuscadorQueries;
use App\Entity\ConsejoMedico;
use App\Entity\SerieRespuesta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConsejoMedicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('codigo', TextType::class, ['label' => 'TRANS_CODIGO_CONSEJO_MEDICO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
	        ->add('descripcion', TextType::class, ['label' => 'TRANS_TEXTO_CONSEJO_ES', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('descripcionCa', TextType::class, ['label' => 'TRANS_TEXTO_CONSEJO_CA', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => true])
            ->add('indicarResumen', CheckboxType::class, ['label' => 'TRANS_INDICAR_RESUMEN', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('indicarInforme', CheckboxType::class, ['label' => 'TRANS_INDICAR_INFORME', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('documento',  FileType::class, ['label' => 'TRANS_DOCUMENTO', 'translation_domain' => 'mantenimientoreconocimientos', 'required' => false, 'mapped' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsejoMedico::class,
        ]);
    }
}
