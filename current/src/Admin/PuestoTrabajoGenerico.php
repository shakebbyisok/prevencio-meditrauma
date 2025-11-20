<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PuestoTrabajoGenerico extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
            ->add('codigo', TextType::class, ['label' => 'Código', 'required' => false])
            ->add('observaciones', TextareaType::class, ['label' => 'Observaciones', 'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false]);

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('codigo', null, ['label' => 'Código'])
            ->add('anulado', null, ['label' => 'Anulado']);
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            ->addIdentifier('codigo', null, ['label' => 'Código'])
            ->add('anulado', null, ['label' => 'Anulado'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}