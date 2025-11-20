<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SmsConfig extends AbstractAdmin
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
	        ->add('activo', CheckboxType::class, ['label' => 'Activo', 'required' => false])
            ->add('remite', TextType::class, ['label' => 'Remitente', 'required' => true])
            ->add('centro', TextType::class, ['label' => 'Centro', 'required' => true])
            ->add('mensaje', TextType::class, ['label' => 'Mensaje', 'required' => true])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('activo', null, ['label' => 'Activo'])
            ->add('remite', null, ['label' => 'Remitente'])
            ->add('centro', null, ['label' => 'Centro']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('activo', null, ['label' => 'Activo'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}