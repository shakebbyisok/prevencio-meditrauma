<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class RemesaConfig extends AbstractAdmin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('ordenante', TextType::class, ['label' => 'Nombre ordenante'])
            ->add('ccc', TextType::class, ['label' => 'CCC'])
            ->add('bic', TextType::class, ['label' => 'BIC'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('ordenante', null, ['label' => 'Nombre ordenante'])
            ->add('ccc', null, ['label' => 'CCC'])
            ->add('bic', null, ['label' => 'BIC']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('ordenante', null, ['label' => 'Nombre ordenante'])
            ->add('ccc', null, ['label' => 'CCC'])
            ->add('bic', null, ['label' => 'BIC'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}