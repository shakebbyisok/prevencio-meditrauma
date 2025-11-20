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

final class Epi extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
            ->add('descripcionEs', TextType::class, ['label' => 'Nombre castellano', 'required' => false])
            ->add('descripcionCa', TextType::class, ['label' => 'Nombre catalán', 'required' => false])
            ->add('empresa', CheckboxType::class, ['label' => 'Empresa', 'required' => false])
            ->add('trabajador', CheckboxType::class, ['label' => 'Trabajador', 'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('descripcionEs', null, ['label' => 'Nombre castellano'])
            ->add('descripcionCa', null, ['label' => 'Nombre catalán'])
            ->add('empresa', null, ['label' => 'Empresa'])
            ->add('trabajador', null, ['label' => 'Trabajador'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
            ->add('empresa', null, ['label' => 'Empresa'])
            ->add('trabajador', null, ['label' => 'Trabajador'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}