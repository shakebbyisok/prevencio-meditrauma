<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;

final class PreventivaEmpresaCausa extends AbstractAdmin
{
    public function __toString(): string
    {
        return $this->getDescripcion();
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        //Peticio 28/07/2023
        $formMapper
            ->add('preventivaEmpresa', ModelType::class, [
                'class' => \App\Entity\PreventivaEmpresa::class,
                'label' => 'Preventiva empresa',
                'required' => true
            ])
            ->add('causa', ModelType::class, [
                'class' => \App\Entity\Causa::class,
                'label' => 'Causa',
                'required' => true,
                'property' => 'fullDescription', // El nombre de la función en la entidad
                'placeholder' => 'Seleccione una causa', // Opcional: Agrega un marcador de posición
            ])
            ->add('anulado', CheckboxType::class, [
                'label' => 'Anulado',
                'required' => false
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('preventivaEmpresa', null, ['label' => 'Preventiva empresa'])
            ->add('causa', null, ['label' => 'Causa'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('preventivaEmpresa', null, ['label' => 'Preventiva empresa'])
            ->add('causa', null, ['label' => 'Causa'])
            ->add('anulado', null, ['label' => 'Anulado'])
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }
}