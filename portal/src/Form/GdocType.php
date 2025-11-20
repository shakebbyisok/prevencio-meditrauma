<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Empresa;
use App\Entity\Centro;
use App\Entity\Facturacion;
use App\Entity\FaseContrato;
use App\Entity\GdocPlantillas;
use App\Entity\GdocPlantillasCarpeta;
use App\Entity\TipoContrato;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GdocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'TRANS_NOMBRE_FICHERO', 'translation_domain' => 'gdoc', 'required' => true])
	        ->add('media', FileType::class, ['label' => 'TRANS_FICHERO', 'translation_domain' => 'gdoc', 'required' => true])
	        ->add('carpeta', EntityType::class, ['class' => GdocPlantillasCarpeta::class, 'required' => true, 'choice_label' => 'nombre', 'empty_data' => null, 'disabled' => false, 'attr' => ['class' => 'select-search']])
	        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GdocPlantillas::class,
	        'empresaId' => null,
	        'empresaObj' => null
        ]);
    }
}
