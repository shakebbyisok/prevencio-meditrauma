<?php

namespace App\Form;

use App\Entity\Empresa;
use App\Entity\GdocPlantillas;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EnviarCuestionarioRevisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('para', TextType::class, ['label' => 'TRANS_CORREO_PARA', 'translation_domain' => 'messages', 'required' => true, 'data' => $options['correo']])
	        ->add('cc', TextType::class, ['label' => 'CC', 'translation_domain' => 'messages', 'required' => false])
	        ->add('cco', TextType::class, ['label' => 'CCC', 'translation_domain' => 'messages', 'required' => false])
	        ->add('asunto', TextType::class, ['label' => 'TRANS_CORREO_ASUNTO', 'translation_domain' => 'messages', 'required' => true])
	        ->add('mensaje', TextareaType::class, ['label' => 'TRANS_CORREO_MENSAJE', 'translation_domain' => 'messages', 'required' => true])
//            ->add('cuestionarioMedico', CheckboxType::class, ['label' => 'TRANS_CUESTIONARIO_MEDICO', 'translation_domain' => 'revision', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
//            ->add('revisionMedica', CheckboxType::class, ['label' => 'TRANS_REVISION_MEDICA', 'translation_domain' => 'revision', 'required' => false, 'attr' => ['class' => 'form-check-input-styled']])
            ->add('fichero',  FileType::class, ['label' => 'TRANS_FICHERO', 'translation_domain' => 'trabajadores', 'required' => true])
            ->add('enviar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'correo' => null
        ]);
    }
}
