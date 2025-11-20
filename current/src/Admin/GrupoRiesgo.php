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

final class GrupoRiesgo extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
            ->add('descripcionEs', TextType::class, ['label' => 'Nombre castellano', 'required' => false])
            ->add('descripcionCa', TextType::class, ['label' => 'Nombre catalán', 'required' => false])
            ->add('codigo', TextType::class, ['label' => 'Código', 'required' => false])
            ->add('anulado', CheckboxType::class, ['label' => 'Anulado', 'required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('descripcion', null, ['label' => 'Nombre'])
            ->add('descripcionEs', null, ['label' => 'Nombre castellano'])
            ->add('descripcionCa', null, ['label' => 'Nombre catalán'])
            ->add('codigo', null, ['label' => 'Código'])
            ->add('anulado', null, ['label' => 'Anulado']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('codigo', null, ['label' => 'Código'])
	        ->add('descripcion', null, ['label' => 'Nombre'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}