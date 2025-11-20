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

final class SerieFactura extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('serie', TextType::class, ['label' => 'Serie', 'required' => false])
	        ->add('descripcion', TextType::class, ['label' => 'Nombre'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('serie', null, ['label' => 'Serie'])
	        ->add('descripcion', null, ['label' => 'Nombre']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('descripcion', null, ['label' => 'Nombre'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}