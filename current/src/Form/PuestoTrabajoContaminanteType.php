<?php

namespace App\Form;

use App\Entity\Centro;
use App\Entity\Empresa;
use App\Entity\GrupoMaquina;
use App\Entity\MaquinaEmpresa;
use App\Entity\MaquinaGenerica;
use App\Entity\PuestoTrabajoCentro;
use App\Entity\PuestoTrabajoContaminante;
use App\Entity\PuestoTrabajoEvaluacion;
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

class PuestoTrabajoContaminanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',  TextType::class, ['label' => 'TRANS_NOMBRE_PRODUCTO', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('cas',  TextType::class, ['label' => 'CAS', 'required' => false])
            ->add('epis',  TextType::class, ['label' => 'Epis', 'required' => false])
            ->add('composicion',  TextType::class, ['label' => 'TRANS_COMPOSICION', 'translation_domain' => 'evaluacion', 'required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PuestoTrabajoContaminante::class,
        ]);
    }
}
