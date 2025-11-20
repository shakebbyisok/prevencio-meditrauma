<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class AnaliticasConfig extends AbstractAdmin
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
	        ->add('url', TextType::class, ['label' => 'URL sftp'])
            ->add('puerto', TextType::class, ['label' => 'Puerto'])
            ->add('usuario', TextType::class, ['label' => 'Usuario'])
            ->add('password', TextType::class, ['label' => 'Contraseña'])
            ->add('carpeta', TextType::class, ['label' => 'Carpeta resultados sftp'])
            ->add('carpetaResultadoAnalitica', TextType::class, ['label' => 'Carpeta resultados analiticas'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('url', null, ['label' => 'URL sftp']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->addIdentifier('url', null, ['label' => 'Ruta'])
	        ->add('_action', 'actions', array(
		        'actions' => array(
			        'edit' => array(),
			        'delete' => array(),
		        )
	        ));
    }
}